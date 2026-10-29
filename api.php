<?php
require 'config.php';

$action = $_GET['action'] ?? '';

if ($action === 'me') {
    $user = current_user();
    respond(['ok' => true, 'user' => $user]);
}

if ($action === 'login') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(['ok' => false, 'error' => 'POST required']);
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    $username = trim($input['username'] ?? '');
    $password = $input['password'] ?? '';
    if ($username === '' || $password === '') {
        respond(['ok' => false, 'error' => 'Username and password are required']);
    }
    $stmt = $conn->prepare("SELECT id, username, password_hash, name, role FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    if (!$user || !password_verify($password, $user['password_hash'])) {
        respond(['ok' => false, 'error' => 'Invalid username or password']);
    }
    session_regenerate_id(true);
    $_SESSION['user'] = ['id' => (int)$user['id'], 'username' => $user['username'], 'name' => $user['name'], 'role' => $user['role']];
    respond(['ok' => true, 'user' => $_SESSION['user']]);
}

if ($action === 'logout') {
    session_destroy();
    respond(['ok' => true]);
}

require_login();

function getStats($conn)
{
    $total = 0;
    $instock = 0;
    $low = 0;
    $value = 0.0;
    $result = $conn->query("SELECT price, qty, min_qty FROM inventory");
    while ($row = $result->fetch_assoc()) {
        $total++;
        if ((int)$row['qty'] > (int)$row['min_qty']) {
            $instock++;
        } else {
            $low++;
        }
        $value += $row['price'] * $row['qty'];
    }
    $cats = [];
    $res = $conn->query("SELECT category, COUNT(*) AS items, SUM(qty) AS units FROM inventory GROUP BY category ORDER BY category");
    while ($row = $res->fetch_assoc()) {
        $cats[] = ['category' => $row['category'], 'items' => (int)$row['items'], 'units' => (int)$row['units']];
    }
    return ['total' => $total, 'instock' => $instock, 'low' => $low, 'value' => round($value, 2), 'by_category' => $cats];
}

function logMovement($conn, $itemId, $itemName, $delta, $note, $username)
{
    $stmt = $conn->prepare("INSERT INTO stock_movements (item_id, item_name, change_qty, note, username) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('isiss', $itemId, $itemName, $delta, $note, $username);
    $stmt->execute();
}

if ($action === 'summary') {
    $d = date('Y-m-d 00:00:00');
    $w = date('Y-m-d 00:00:00', strtotime('monday this week'));
    $m = date('Y-m-01 00:00:00');
    $y = date('Y-01-01 00:00:00');
    $stmt = $conn->prepare(
        "SELECT
            COALESCE(SUM(CASE WHEN change_qty > 0 AND created_at >= ? THEN change_qty ELSE 0 END), 0) AS day_in,
            COALESCE(SUM(CASE WHEN change_qty < 0 AND created_at >= ? THEN -change_qty ELSE 0 END), 0) AS day_out,
            COALESCE(SUM(CASE WHEN change_qty > 0 AND created_at >= ? THEN change_qty ELSE 0 END), 0) AS week_in,
            COALESCE(SUM(CASE WHEN change_qty < 0 AND created_at >= ? THEN -change_qty ELSE 0 END), 0) AS week_out,
            COALESCE(SUM(CASE WHEN change_qty > 0 AND created_at >= ? THEN change_qty ELSE 0 END), 0) AS month_in,
            COALESCE(SUM(CASE WHEN change_qty < 0 AND created_at >= ? THEN -change_qty ELSE 0 END), 0) AS month_out,
            COALESCE(SUM(CASE WHEN change_qty > 0 AND created_at >= ? THEN change_qty ELSE 0 END), 0) AS year_in,
            COALESCE(SUM(CASE WHEN change_qty < 0 AND created_at >= ? THEN -change_qty ELSE 0 END), 0) AS year_out,
            COALESCE(SUM(CASE WHEN change_qty > 0 THEN change_qty ELSE 0 END), 0) AS all_in,
            COALESCE(SUM(CASE WHEN change_qty < 0 THEN -change_qty ELSE 0 END), 0) AS all_out
        FROM stock_movements"
    );
    $types = 'ssssssss';
    $params = [$d, $d, $w, $w, $m, $m, $y, $y];
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    respond([
        'ok' => true,
        'summary' => [
            'day' => ['in' => (int)$r['day_in'], 'out' => (int)$r['day_out']],
            'week' => ['in' => (int)$r['week_in'], 'out' => (int)$r['week_out']],
            'month' => ['in' => (int)$r['month_in'], 'out' => (int)$r['month_out']],
            'year' => ['in' => (int)$r['year_in'], 'out' => (int)$r['year_out']],
            'all' => ['in' => (int)$r['all_in'], 'out' => (int)$r['all_out']],
        ],
    ]);
}

if ($action === 'list') {
    $search = '%' . trim($_GET['search'] ?? '') . '%';
    $cat = trim($_GET['category'] ?? '');
    if ($cat !== '') {
        $stmt = $conn->prepare("SELECT * FROM inventory WHERE name LIKE ? AND category = ? ORDER BY name");
        $stmt->bind_param('ss', $search, $cat);
    } else {
        $stmt = $conn->prepare("SELECT * FROM inventory WHERE name LIKE ? ORDER BY name");
        $stmt->bind_param('s', $search);
    }
    $stmt->execute();
    $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    respond(['ok' => true, 'items' => $items, 'stats' => getStats($conn)]);
}

if ($action === 'history') {
    $itemId = (int)($_GET['item_id'] ?? 0);
    $period = $_GET['period'] ?? 'all';
    $page = max(1, (int)($_GET['page'] ?? 1));
    $limit = 20;
    $offset = ($page - 1) * $limit;

    $periodStarts = [
        'day' => date('Y-m-d 00:00:00'),
        'week' => date('Y-m-d 00:00:00', strtotime('monday this week')),
        'month' => date('Y-m-01 00:00:00'),
        'year' => date('Y-01-01 00:00:00'),
    ];

    $where = [];
    $types = '';
    $params = [];
    if ($itemId > 0) {
        $where[] = "item_id = ?";
        $types .= 'i';
        $params[] = $itemId;
    }
    if (isset($periodStarts[$period])) {
        $where[] = "created_at >= ?";
        $types .= 's';
        $params[] = $periodStarts[$period];
    }
    $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    $stmt = $conn->prepare("SELECT COALESCE(SUM(CASE WHEN change_qty > 0 THEN change_qty ELSE 0 END), 0) AS total_in, COALESCE(SUM(CASE WHEN change_qty < 0 THEN -change_qty ELSE 0 END), 0) AS total_out, COUNT(*) AS cnt FROM stock_movements $whereSql");
    if ($types !== '') $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $totals = $stmt->get_result()->fetch_assoc();
    $count = (int)$totals['cnt'];
    $pages = max(1, (int)ceil($count / $limit));
    $page = min($page, $pages);

    $stmt = $conn->prepare("SELECT * FROM stock_movements $whereSql ORDER BY created_at DESC, id DESC LIMIT $limit OFFSET " . (($page - 1) * $limit));
    if ($types !== '') $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $moves = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    respond([
        'ok' => true,
        'movements' => $moves,
        'period' => isset($periodStarts[$period]) ? $period : 'all',
        'since' => $periodStarts[$period] ?? null,
        'total_in' => (int)$totals['total_in'],
        'total_out' => (int)$totals['total_out'],
        'count' => $count,
        'page' => $page,
        'pages' => $pages,
    ]);
}

if ($action === 'export') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="boostcafe-inventory-' . date('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');
    fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

    // ===== SECTION 1: CURRENT INVENTORY =====
    fputcsv($out, ['=== INVENTORY ===']);
    fputcsv($out, ['ID', 'Item Name', 'Category', 'Price (PHP)', 'Qty', 'Min Qty', 'Status', 'Stock Value (PHP)']);
    $result = $conn->query("SELECT * FROM inventory ORDER BY name");
    while ($row = $result->fetch_assoc()) {
        $status = (int)$row['qty'] <= 0 ? 'OUT OF STOCK' : ((int)$row['qty'] <= (int)$row['min_qty'] ? 'LOW STOCK' : 'IN STOCK');
        fputcsv($out, [$row['id'], $row['name'], $row['category'], $row['price'], $row['qty'], $row['min_qty'], $status, number_format($row['price'] * $row['qty'], 2, '.', '')]);
    }
    fputcsv($out, []);

    // ===== SECTION 2: ALL IN / OUT PER DAY (WITH TIME AND ITEM) =====
    fputcsv($out, ['=== IN / OUT PER DAY ===']);
    fputcsv($out, ['Date', 'Time', 'Item', 'Action', 'Qty']);
    $result = $conn->query("SELECT item_name, change_qty, created_at FROM stock_movements ORDER BY created_at ASC, id ASC");
    $rows = $result->fetch_all(MYSQLI_ASSOC);

    $grandIn = 0;
    $grandOut = 0;
    $curDay = null;
    $dayIn = 0;
    $dayOut = 0;

    $txtStamp = function ($fmt, $dt) {
        return str_replace(' ', "\u{00A0}", date($fmt, strtotime($dt)));
    };

    $flushDayTotal = function () use ($out, &$curDay, &$dayIn, &$dayOut, $txtStamp) {
        if ($curDay === null) return;
        $net = $dayIn - $dayOut;
        fputcsv($out, [
            $txtStamp('M j, Y', $curDay),
            '',
            'DAY TOTAL',
            'IN +' . $dayIn . ' / OUT -' . $dayOut,
            ($net >= 0 ? '+' : '') . $net
        ]);
        fputcsv($out, []);
    };

    foreach ($rows as $row) {
        $day = substr($row['created_at'], 0, 10);
        if ($curDay !== null && $day !== $curDay) {
            $flushDayTotal();
            $dayIn = 0;
            $dayOut = 0;
        }
        $curDay = $day;

        $qty = (int)$row['change_qty'];
        $dir = $qty > 0 ? 'IN' : ($qty < 0 ? 'OUT' : 'DELETED');
        if ($qty > 0) {
            $dayIn += $qty;
            $grandIn += $qty;
        } elseif ($qty < 0) {
            $dayOut += -$qty;
            $grandOut += -$qty;
        }

        fputcsv($out, [
            $txtStamp('M j, Y', $row['created_at']),
            $txtStamp('g:i A', $row['created_at']),
            $row['item_name'],
            $dir,
            ($qty > 0 ? '+' : '') . $qty
        ]);
    }
    $flushDayTotal();

    if ($grandIn > 0 || $grandOut > 0) {
        $net = $grandIn - $grandOut;
        fputcsv($out, ['GRAND TOTAL', '', '', 'IN +' . $grandIn . ' / OUT -' . $grandOut, ($net >= 0 ? '+' : '') . $net]);
    }

    fclose($out);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true) ?? [];
$username = current_user()['username'];

if ($action === 'adjust') {
    foreach (['id', 'delta'] as $f) {
        if (!isset($input[$f])) respond(['ok' => false, 'error' => "Missing $f"]);
    }
    $id = (int)$input['id'];
    $delta = (int)$input['delta'];
    $note = trim($input['note'] ?? '');
    if ($delta === 0) respond(['ok' => false, 'error' => 'Quantity change cannot be zero']);
    if (mb_strlen($note) > 255) respond(['ok' => false, 'error' => 'Note is too long']);
    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("SELECT name, qty FROM inventory WHERE id = ? FOR UPDATE");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $item = $stmt->get_result()->fetch_assoc();
        if (!$item) throw new Exception('Item not found');
        $newQty = max(0, (int)$item['qty'] + $delta);
        $applied = $newQty - (int)$item['qty'];
        if ($applied === 0) throw new Exception('No change applied (stock already at zero)');
        $stmt = $conn->prepare("UPDATE inventory SET qty = ? WHERE id = ?");
        $stmt->bind_param('ii', $newQty, $id);
        $stmt->execute();
        logMovement($conn, $id, $item['name'], $applied, $note, $username);
        $conn->commit();
        respond(['ok' => true, 'new_qty' => $newQty, 'applied' => $applied]);
    } catch (Exception $e) {
        $conn->rollback();
        respond(['ok' => false, 'error' => $e->getMessage()]);
    }
}

if ($action === 'add') {
    if (!isset($input['name'], $input['category'], $input['price'], $input['qty'], $input['min'])) {
        respond(['ok' => false, 'error' => 'Missing fields']);
    }
    $name = trim($input['name']);
    if ($name === '') {
        respond(['ok' => false, 'error' => 'Item name is required']);
    }
    if (mb_strlen($name) > 255) {
        respond(['ok' => false, 'error' => 'Item name is too long']);
    }
    $qty = max(0, (int)$input['qty']);
    $price = max(0, (float)$input['price']);
    $min = max(0, (int)$input['min']);
    $category = trim($input['category']);

    // Duplicate check: prevent adding a product that already exists
    $stmt = $conn->prepare("SELECT id FROM inventory WHERE name = ? LIMIT 1");
    $stmt->bind_param('s', $name);
    $stmt->execute();
    if ($stmt->get_result()->fetch_assoc()) {
        respond(['ok' => false, 'error' => '"' . $name . '" already exists in inventory']);
    }

    $stmt = $conn->prepare("INSERT INTO inventory (name, category, price, qty, min_qty) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('ssdii', $name, $category, $price, $qty, $min);
    $stmt->execute();
    if ($qty > 0) {
        logMovement($conn, $stmt->insert_id, $name, $qty, 'Initial stock', $username);
    }
    respond(['ok' => true, 'id' => $stmt->insert_id]);
}

if ($action === 'update') {
    if (!isset($input['id'])) {
        respond(['ok' => false, 'error' => 'Missing id']);
    }
    $name = trim($input['name'] ?? '');
    if ($name === '') {
        respond(['ok' => false, 'error' => 'Item name is required']);
    }
    $id = (int)$input['id'];
    $price = max(0, (float)$input['price']);
    $qty = max(0, (int)$input['qty']);
    $min = max(0, (int)$input['min']);

    // Duplicate check: name must not match another existing item
    $stmt = $conn->prepare("SELECT id FROM inventory WHERE name = ? AND id != ? LIMIT 1");
    $stmt->bind_param('si', $name, $id);
    $stmt->execute();
    if ($stmt->get_result()->fetch_assoc()) {
        respond(['ok' => false, 'error' => '"' . $name . '" already exists in inventory']);
    }

    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("SELECT name, qty FROM inventory WHERE id = ? FOR UPDATE");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $old = $stmt->get_result()->fetch_assoc();
        if (!$old) throw new Exception('Item not found');

        $stmt = $conn->prepare("UPDATE inventory SET name=?, category=?, price=?, qty=?, min_qty=? WHERE id=?");
        $stmt->bind_param('ssdiii', $name, $input['category'], $price, $qty, $min, $id);
        $stmt->execute();

        $delta = $qty - (int)$old['qty'];
        if ($delta !== 0) {
            logMovement($conn, $id, $name, $delta, 'Qty edited via item update', $username);
        }

        $conn->commit();
        respond(['ok' => true]);
    } catch (Exception $e) {
        $conn->rollback();
        respond(['ok' => false, 'error' => $e->getMessage()]);
    }
}

if ($action === 'delete') {
    if (!isset($input['id'])) {
        respond(['ok' => false, 'error' => 'Missing id']);
    }
    $stmt = $conn->prepare("SELECT name FROM inventory WHERE id = ?");
    $stmt->bind_param('i', $input['id']);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();
    $stmt = $conn->prepare("DELETE FROM inventory WHERE id=?");
    $stmt->bind_param('i', $input['id']);
    $stmt->execute();
    if ($item) {
        logMovement($conn, (int)$input['id'], $item['name'], 0, 'Item deleted', $username);
    }
    respond(['ok' => true]);
}

respond(['ok' => false, 'error' => 'Invalid action']);
