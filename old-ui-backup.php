<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BoostCafe — Inventory System</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Crect width='16' height='16' rx='3' fill='%2316305e'/%3E%3Cpath transform='translate(2.8 2.2) scale(0.68)' fill='%23ffffff' d='M11.251.068a.5.5 0 0 1 .227.58L9.677 6.5H13a.5.5 0 0 1 .364.843l-8 8.5a.5.5 0 0 1-.842-.49L6.323 9.5H3a.5.5 0 0 1-.364-.843l8-8.5a.5.5 0 0 1 .615-.09z'/%3E%3C/svg%3E">
<style>
:root {
  --bg: #f4f6f9;
  --panel: #ffffff;
  --panel-2: #f8fafc;
  --panel-3: #eef2f7;
  --border: #e3e8ef;
  --border-strong: #cbd5e1;
  --text: #16233b;
  --muted: #5f6f85;
  --dim: #93a1b5;
  --navy: #16305e;
  --navy-2: #1f4287;
  --blue: #2563eb;
  --blue-soft: #dbe6fb;
  --green-bg: #dff3e6; --green-tx: #157347;
  --amber-bg: #fdf3d8; --amber-tx: #a16207;
  --red-bg: #fdeaea; --red-tx: #bb2d3b;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
  font-family: "Inter", sans-serif;
  background: var(--bg);
  color: var(--text);
  line-height: 1.55;
  min-height: 100vh;
}
.container { max-width: 1160px; margin: 0 auto; padding: 0 24px; }
a { color: inherit; text-decoration: none; }
button { font-family: inherit; cursor: pointer; }
h1, h2, h3, .oswald, .serif { font-family: "Inter", sans-serif; letter-spacing: -.01em; }

.hazard {
  height: 4px;
  background: linear-gradient(90deg, var(--navy), var(--blue));
}

/* ============ LOGIN ============ */
#login-view {
  position: fixed; inset: 0;
  display: flex; align-items: center; justify-content: center;
  padding: 20px;
  z-index: 500;
  background: var(--bg);
}
.login-box {
  width: 100%; max-width: 400px;
  background: var(--panel);
  border: 1px solid var(--border);
  border-radius: 14px;
  overflow: hidden;
  box-shadow: 0 18px 50px rgba(22,35,59,.12);
}
.login-stripe { height: 5px; background: linear-gradient(90deg, var(--navy), var(--blue)); }
.login-inner { padding: 38px 36px 32px; }
.logo-mark { font-family: "Inter", sans-serif; font-weight: 800; font-size: 1.55rem; letter-spacing: -.02em; text-align: center; color: var(--navy); }
.logo-mark span { color: var(--blue); }
.login-sub { color: var(--muted); font-size: .68rem; margin-top: 7px; letter-spacing: .22em; text-transform: uppercase; text-align: center; }
.login-form { margin-top: 28px; display: grid; gap: 16px; }
.field label { display: block; font-size: .68rem; font-weight: 600; text-transform: uppercase; letter-spacing: .12em; color: var(--muted); margin-bottom: 6px; }
.field input {
  width: 100%;
  padding: 11px 14px;
  background: #ffffff;
  border: 1px solid var(--border-strong);
  border-radius: 8px;
  color: var(--text);
  font-family: inherit;
  font-size: .95rem;
  outline: none;
  transition: border-color .15s, box-shadow .15s;
}
.field input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,.15); }
.login-error {
  display: none;
  background: var(--red-bg);
  color: var(--red-tx);
  border: 1px solid rgba(187,45,59,.3);
  border-radius: 8px;
  font-weight: 600;
  font-size: .82rem;
  padding: 9px 12px;
}
.cred-hint {
  margin-top: 20px;
  border: 1px dashed var(--border-strong);
  border-radius: 8px;
  padding: 10px 12px;
  font-size: .74rem;
  color: var(--muted);
  text-align: center;
}
.cred-hint b { color: var(--text); }

/* ============ HEADER ============ */
header { background: var(--panel); border-bottom: 1px solid var(--border); }
nav { display: flex; align-items: center; justify-content: space-between; height: 64px; gap: 14px; }
.brand { font-family: "Inter", sans-serif; font-weight: 700; font-size: 1.18rem; letter-spacing: -.01em; display: flex; align-items: center; color: var(--navy); }
.brand em {
  font-style: normal; background: var(--navy); color: #ffffff;
  width: 34px; height: 34px; border-radius: 8px;
  display: inline-flex; align-items: center; justify-content: center;
  font-size: .82rem; font-weight: 800; letter-spacing: .02em;
  margin-right: 10px;
}
.brand small { color: var(--muted); font-weight: 500; text-transform: uppercase; letter-spacing: .16em; font-size: .66rem; margin-left: 12px; }
.user-chip { display: flex; align-items: center; gap: 12px; }
.user-chip .who { text-align: right; line-height: 1.25; }
.user-chip .who b { font-size: .86rem; display: block; }
.user-chip .who small { color: var(--muted); font-size: .64rem; text-transform: uppercase; letter-spacing: .12em; }

/* ============ BUTTONS ============ */
.btn {
  display: inline-flex; align-items: center; gap: 7px;
  border: 1px solid var(--border-strong);
  background: var(--panel);
  color: var(--text);
  font-weight: 600;
  font-size: .82rem;
  padding: 9px 16px;
  border-radius: 8px;
  transition: all .15s;
}
.btn:hover { background: var(--panel-2); border-color: var(--dim); }
.btn-primary { background: var(--navy); color: #ffffff; border-color: var(--navy); }
.btn-primary:hover { background: var(--navy-2); border-color: var(--navy-2); }
.btn-ghost { background: transparent; }
.btn-sm { padding: 5px 11px; font-size: .74rem; }
.btn-icon { padding: 5px 11px; font-size: .85rem; font-weight: 700; }
.btn-danger:hover { background: var(--red-bg); color: var(--red-tx); border-color: rgba(187,45,59,.4); }

main { padding: 30px 0 70px; }

/* ============ STATS ============ */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 14px; margin-bottom: 14px; }
.stat-card {
  position: relative;
  background: var(--panel);
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 16px 18px;
  box-shadow: 0 1px 2px rgba(22,35,59,.04);
}
.stat-card small { color: var(--muted); font-size: .66rem; font-weight: 600; text-transform: uppercase; letter-spacing: .13em; }
.stat-card strong { display: block; font-size: 1.65rem; font-weight: 700; letter-spacing: -.02em; margin-top: 4px; font-variant-numeric: tabular-nums; }
.stat-card.inv strong { background: var(--blue-soft); color: var(--navy); display: inline-block; padding: 0 12px; border-radius: 8px; }

.move-line { display: flex; align-items: baseline; gap: 7px; margin-top: 7px; font-size: .7rem; color: var(--muted); text-transform: uppercase; letter-spacing: .08em; flex-wrap: wrap; }
.move-line b { font-weight: 700; background: var(--green-bg); color: var(--green-tx); border-radius: 6px; padding: 1px 8px; font-size: .92rem; }
.move-line b.neg { background: var(--red-bg); color: var(--red-tx); }

/* ============ PANELS ============ */
.panel { background: var(--panel); border: 1px solid var(--border); border-radius: 12px; margin-bottom: 16px; box-shadow: 0 1px 3px rgba(22,35,59,.05); overflow: hidden; }
.panel-head {
  display: flex; align-items: center; justify-content: space-between;
  gap: 14px; flex-wrap: wrap;
  padding: 16px 20px;
  border-bottom: 1px solid var(--border);
}
.panel-head h2 { font-size: 1.05rem; font-weight: 700; display: flex; align-items: center; gap: 10px; }
.panel-head h2::before { content: ""; width: 4px; height: 17px; border-radius: 2px; background: var(--blue); }
.controls { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }

.search-input, select {
  padding: 8px 13px;
  background: #ffffff;
  border: 1px solid var(--border-strong);
  border-radius: 8px;
  color: var(--text);
  font-family: inherit;
  font-size: .86rem;
  outline: none;
  transition: border-color .15s, box-shadow .15s;
}
.search-input:focus, select:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
.search-input { min-width: 190px; }

/* ============ TABLE ============ */
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: .87rem; }
th, td { padding: 12px 18px; text-align: left; }
th {
  font-size: .67rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: .11em;
  color: var(--muted);
  background: var(--panel-2);
  border-bottom: 1px solid var(--border);
  white-space: nowrap;
  user-select: none;
}
th.sortable { cursor: pointer; }
th.sortable:hover { color: var(--navy); }
th .arrow { color: var(--blue); margin-left: 4px; }
td { border-bottom: 1px solid var(--border); vertical-align: middle; }
tbody tr:last-child td { border-bottom: none; }
tbody tr:hover { background: #f6f9fd; }
.item-name { font-weight: 600; }
.mono-num { font-variant-numeric: tabular-nums; }
.qty-low { color: var(--amber-tx); font-weight: 700; }
.qty-ok { color: var(--muted); }
.status {
  display: inline-block;
  font-size: .63rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .07em;
  padding: 4px 11px;
  border-radius: 999px;
  white-space: nowrap;
}
.st-ok { background: var(--green-bg); color: var(--green-tx); }
.st-low { background: var(--amber-bg); color: var(--amber-tx); }
.st-out { background: var(--red-bg); color: var(--red-tx); border: 1px solid rgba(187,45,59,.3); }
.actions-cell { text-align: right; white-space: nowrap; }
.row-actions { display: inline-flex; gap: 5px; }

.empty-state { text-align: center; padding: 46px 20px; color: var(--muted); font-size: .9rem; }
.empty-state .icon { font-size: 1.8rem; margin-bottom: 8px; }

/* ============ CHART ============ */
.chart-body { padding: 20px; display: grid; gap: 13px; }
.chart-row { display: grid; grid-template-columns: 150px 1fr 110px; align-items: center; gap: 12px; font-size: .8rem; }
.chart-label { color: var(--muted); text-transform: uppercase; letter-spacing: .06em; font-size: .7rem; font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; text-align: right; }
.chart-track { background: #ffffff; border: 1px solid var(--border-strong); border-radius: 999px; height: 18px; position: relative; overflow: hidden; }
.chart-bar {
  height: 100%;
  background: linear-gradient(90deg, var(--navy-2), var(--blue));
  transition: width .5s ease;
  min-width: 0;
}
.chart-val { font-size: .78rem; color: var(--text); font-variant-numeric: tabular-nums; }
.chart-empty { color: var(--dim); font-size: .82rem; padding: 6px 0; }

/* ============ HISTORY ============ */
.hist-change-pos { color: var(--green-tx); font-weight: 700; }
.hist-change-neg { color: var(--red-tx); font-weight: 600; }
.hist-note { color: var(--muted); }
.hist-user { color: var(--dim); font-size: .78rem; }
.seg { display: grid; grid-template-columns: 1fr 1fr; border: 1px solid var(--border-strong); border-radius: 8px; overflow: hidden; }
.seg button {
  padding: 10px;
  background: #ffffff;
  border: none;
  color: var(--muted);
  font-weight: 600;
  font-size: .8rem;
  transition: all .15s;
}
.seg button.active { background: var(--navy); color: #ffffff; }
.seg button:last-child { border-left: 1px solid var(--border-strong); }
.seg-period { display: flex; border: 1px solid var(--border-strong); border-radius: 8px; overflow: hidden; }
.seg-period button {
  padding: 7px 15px;
  background: #ffffff;
  border: none;
  color: var(--muted);
  font-weight: 600;
  font-size: .74rem;
  transition: all .15s;
}
.seg-period button + button { border-left: 1px solid var(--border-strong); }
.seg-period button.active { background: var(--navy); color: #ffffff; }
.hist-summary {
  display: flex; gap: 20px; flex-wrap: wrap;
  padding: 11px 18px;
  border-bottom: 1px solid var(--border);
  background: var(--panel-2);
  font-size: .7rem;
  font-weight: 600;
  color: var(--muted);
  text-transform: uppercase;
  letter-spacing: .1em;
}
.hist-summary b { color: var(--text); letter-spacing: 0; }
.hist-summary .hs-in b { background: var(--green-bg); color: var(--green-tx); border-radius: 6px; padding: 1px 8px; }
.hist-pager {
  display: none;
  align-items: center;
  justify-content: center;
  gap: 18px;
  padding: 12px;
  border-top: 1px solid var(--border);
  font-size: .72rem;
  color: var(--muted);
  text-transform: uppercase;
  letter-spacing: .1em;
}
.hist-pager.show { display: flex; }
#hist-prev[disabled], #hist-next[disabled] { opacity: .3; pointer-events: none; }

/* ============ TOAST ============ */
.toast {
  position: fixed; bottom: 22px; right: 22px;
  background: var(--navy);
  color: #ffffff;
  border-left: 4px solid var(--blue);
  border-radius: 10px;
  padding: 13px 20px;
  font-size: .86rem;
  font-weight: 600;
  box-shadow: 0 14px 34px rgba(22,35,59,.3);
  opacity: 0;
  transform: translateY(10px);
  transition: all .25s;
  pointer-events: none;
  z-index: 300;
}
.toast.show { opacity: 1; transform: none; }

/* ============ MODALS ============ */
.modal-overlay {
  position: fixed; inset: 0;
  background: rgba(22, 35, 59, .5);
  display: none;
  align-items: center;
  justify-content: center;
  padding: 20px;
  z-index: 200;
}
.modal-overlay.open { display: flex; }
.modal {
  background: var(--panel);
  border: 1px solid var(--border);
  border-radius: 14px;
  width: 100%; max-width: 450px;
  animation: pop .15s ease;
  overflow: hidden;
  box-shadow: 0 24px 60px rgba(22,35,59,.25);
}
@keyframes pop { from { transform: scale(.96); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.modal-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 16px 22px;
  border-bottom: 1px solid var(--border);
}
.modal-head h3 { font-size: 1.05rem; font-weight: 700; display: flex; align-items: center; gap: 10px; }
.modal-head h3::before { content: ""; width: 4px; height: 17px; border-radius: 2px; background: var(--blue); }
.modal-close { background: none; border: none; color: var(--muted); font-size: 1.2rem; line-height: 1; padding: 4px; }
.modal-close:hover { color: var(--text); }
.modal-body { padding: 22px; }
.form-grid { display: grid; gap: 15px; }
.form-grid label { font-size: .66rem; font-weight: 600; text-transform: uppercase; letter-spacing: .12em; color: var(--muted); display: block; margin-bottom: 6px; }
.form-grid input, .form-grid select {
  width: 100%;
  padding: 10px 14px;
  background: #ffffff;
  border: 1px solid var(--border-strong);
  border-radius: 8px;
  color: var(--text);
  font-family: inherit;
  font-size: .92rem;
  outline: none;
  transition: border-color .15s, box-shadow .15s;
}
.form-grid input:focus, .form-grid select:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
.row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
.modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 22px; }
.adjust-item-name { font-weight: 700; margin-bottom: 14px; padding: 11px 14px; background: var(--panel-2); border-left: 4px solid var(--blue); border-radius: 8px; font-size: .92rem; }

/* confirm review */
.confirm-title { text-transform: uppercase; letter-spacing: .12em; font-size: .7rem; font-weight: 600; color: var(--muted); margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
.confirm-title::before { content: ""; width: 26px; height: 4px; border-radius: 2px; background: var(--blue); }
.confirm-rows { border: 1px solid var(--border-strong); border-radius: 10px; overflow: hidden; }
.confirm-row { display: flex; justify-content: space-between; gap: 14px; padding: 10px 14px; font-size: .87rem; border-bottom: 1px solid var(--border); }
.confirm-row:last-child { border-bottom: none; }
.confirm-row span { color: var(--muted); font-size: .68rem; font-weight: 600; text-transform: uppercase; letter-spacing: .11em; align-self: center; }
.confirm-row b { text-align: right; }
.confirm-row.big b { font-size: 1.05rem; }
.confirm-warn {
  display: none;
  margin-top: 14px;
  border: 1px dashed rgba(161,98,7,.45);
  border-left: 4px solid var(--amber-tx);
  background: var(--amber-bg);
  border-radius: 8px;
  padding: 10px 12px;
  font-size: .8rem;
  color: var(--text);
}

footer {
  border-top: 1px solid var(--border);
  background: var(--panel);
  padding: 18px 0;
  text-align: center;
  color: var(--dim);
  font-size: .7rem;
  text-transform: uppercase;
  letter-spacing: .2em;
}

@media (max-width: 720px) {
  th:nth-child(2), td:nth-child(2) { display: none; }
  .chart-row { grid-template-columns: 100px 1fr 80px; }
  .brand small { display: none; }
  .user-chip .who { display: none; }
}
</style>
</head>
<body>

<!-- LOGIN VIEW -->
<div id="login-view" style="display:none;">
  <div class="login-box">
    <div class="login-stripe"></div>
    <div class="login-inner">
      <div class="logo-mark">Boost<span>Cafe</span></div>
      <div class="login-sub">Inventory Management System</div>
      <form class="login-form" id="login-form">
        <div class="field">
          <label for="l-user">Username</label>
          <input type="text" id="l-user" autocomplete="username" required>
        </div>
        <div class="field">
          <label for="l-pass">Password</label>
          <input type="password" id="l-pass" autocomplete="current-password" required>
        </div>
        <div class="login-error" id="login-error"></div>
        <button type="submit" class="btn btn-primary" style="justify-content:center; padding:12px;">Sign In</button>
      </form>
      <div class="cred-hint">DEFAULT LOGIN &mdash; user: <b>admin</b> &nbsp;/&nbsp; pass: <b>admin123</b></div>
    </div>
  </div>
</div>

<!-- APP VIEW -->
<div id="app-view" style="display:none;">
  <div class="hazard"></div>
  <header>
    <div class="container">
      <nav>
        <div class="brand"><em>BC</em>BoostCafe<small>Inventory Management System</small></div>
        <div class="user-chip">
          <div class="who"><b id="u-name"></b><small id="u-role"></small></div>
          <button class="btn btn-sm btn-ghost" id="btn-logout">Logout</button>
        </div>
      </nav>
    </div>
  </header>

  <main class="container">
    <div class="stats-grid">
      <div class="stat-card"><small>Total Items</small><strong id="stat-total">-</strong></div>
      <div class="stat-card"><small>In Stock</small><strong id="stat-instock">-</strong></div>
      <div class="stat-card"><small>Low / Out</small><strong id="stat-low">-</strong></div>
      <div class="stat-card inv"><small>Total Value</small><strong id="stat-value">-</strong></div>
    </div>

    <div class="stats-grid">
      <div class="stat-card"><small>Stock In / Out &mdash; This Week</small><div class="move-line"><b id="mv-week-in">-</b><span>in</span><b class="neg" id="mv-week-out">-</b><span>out</span></div></div>
      <div class="stat-card"><small>&mdash; This Month</small><div class="move-line"><b id="mv-month-in">-</b><span>in</span><b class="neg" id="mv-month-out">-</b><span>out</span></div></div>
      <div class="stat-card"><small>&mdash; This Year</small><div class="move-line"><b id="mv-year-in">-</b><span>in</span><b class="neg" id="mv-year-out">-</b><span>out</span></div></div>
      <div class="stat-card"><small>&mdash; All Time</small><div class="move-line"><b id="mv-all-in">-</b><span>in</span><b class="neg" id="mv-all-out">-</b><span>out</span></div></div>
    </div>

    <div class="panel">
      <div class="panel-head">
        <h2>Stock List</h2>
        <div class="controls">
          <input type="text" id="search" class="search-input" placeholder="Search items...">
          <select id="filter-cat">
            <option value="">All Categories</option>
            <option>Coffee Beans</option>
            <option>Milk &amp; Cream</option>
            <option>Syrups</option>
            <option>Pastries</option>
            <option>Packaging</option>
            <option>Other</option>
          </select>
          <button class="btn" id="btn-export">Export CSV</button>
          <button class="btn btn-primary" id="btn-add">+ Add Item</button>
        </div>
      </div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th class="sortable" data-sort="name">Item<span class="arrow"></span></th>
              <th class="sortable" data-sort="category">Category<span class="arrow"></span></th>
              <th class="sortable" data-sort="price">Price<span class="arrow"></span></th>
              <th class="sortable" data-sort="qty">Qty<span class="arrow"></span></th>
              <th>Status</th>
              <th style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody id="table-body"></tbody>
        </table>
      </div>
      <div class="empty-state" id="empty-state" style="display:none;"></div>
    </div>

    <div class="panel">
      <div class="panel-head">
        <h2>Stock by Category</h2>
      </div>
      <div class="chart-body" id="chart-body"><div class="chart-empty">No data.</div></div>
    </div>

    <div class="panel">
      <div class="panel-head">
        <h2>Movement History</h2>
        <div class="controls">
          <button class="btn btn-sm btn-ghost" id="btn-hist-refresh">Refresh</button>
        </div>
      </div>
      <div style="display:flex; justify-content:center; padding:14px 16px; border-bottom:1px solid var(--border);">
        <div class="seg-period" id="hist-period">
          <button data-p="day">Day</button>
          <button data-p="week">Week</button>
          <button data-p="month">Month</button>
          <button data-p="year">Year</button>
          <button data-p="all" class="active">All Time</button>
        </div>
      </div>
      <div class="hist-summary" id="hist-summary" style="display:none;"></div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Date / Time</th>
              <th>Item</th>
              <th>Change</th>
              <th>Note</th>
              <th>User</th>
            </tr>
          </thead>
          <tbody id="hist-body"></tbody>
        </table>
      </div>
      <div class="empty-state" id="hist-empty" style="display:none;">No movements recorded in this period.</div>
      <div class="hist-pager" id="hist-pager">
        <button class="btn btn-sm" id="hist-prev">&#9664; Prev</button>
        <span id="hist-page-info"></span>
        <button class="btn btn-sm" id="hist-next">Next &#9654;</button>
      </div>
    </div>
  </main>

  <footer>BoostCafe Inventory System &middot; PHP + MySQL &middot; 2026</footer>
</div>

<div class="toast" id="toast"></div>

<!-- ADD/EDIT MODAL -->
<div class="modal-overlay" id="modal-overlay">
  <div class="modal">
    <div class="modal-head">
      <h3 id="modal-title">Add Item</h3>
      <button type="button" class="modal-close" id="btn-x">&times;</button>
    </div>
    <div class="modal-body">
      <form id="item-form">
        <div class="form-grid">
          <div>
            <label for="f-name">Item name</label>
            <input type="text" id="f-name" required placeholder="e.g. Arabica Beans 1kg">
          </div>
          <div class="row-2">
            <div>
              <label for="f-category">Category</label>
              <select id="f-category">
                <option>Coffee Beans</option>
                <option>Milk &amp; Cream</option>
                <option>Syrups</option>
                <option>Pastries</option>
                <option>Packaging</option>
                <option>Other</option>
              </select>
            </div>
            <div>
              <label for="f-unit-price">Price</label>
              <input type="number" id="f-unit-price" min="0" step="0.01" required placeholder="0.00">
            </div>
          </div>
          <div class="row-2">
            <div>
              <label for="f-qty">Quantity</label>
              <input type="number" id="f-qty" min="0" required placeholder="0">
            </div>
            <div>
              <label for="f-min">Low-stock alert at</label>
              <input type="number" id="f-min" min="0" value="5" required>
            </div>
          </div>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn btn-ghost" id="btn-cancel">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Item</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ADJUST STOCK MODAL -->
<div class="modal-overlay" id="adjust-overlay">
  <div class="modal">
    <div class="modal-head">
      <h3>Adjust Stock</h3>
      <button type="button" class="modal-close" id="adj-x">&times;</button>
    </div>
    <div class="modal-body">

      <!-- STEP 1: FORM -->
      <div id="adj-step-form">
        <div class="adjust-item-name" id="adj-item-name"></div>
        <form id="adjust-form">
          <div class="form-grid">
            <div>
              <label>Direction</label>
              <div class="seg">
                <button type="button" id="seg-in" class="active">+ Stock In</button>
                <button type="button" id="seg-out">&minus; Stock Out</button>
              </div>
            </div>
            <div class="row-2">
              <div>
                <label for="adj-qty">Quantity</label>
                <input type="number" id="adj-qty" min="1" value="1" required>
              </div>
              <div>
                <label for="adj-current">Current Qty</label>
                <input type="text" id="adj-current" disabled>
              </div>
            </div>
            <div>
              <label for="adj-note">Note (optional)</label>
              <input type="text" id="adj-note" maxlength="255" placeholder="e.g. delivery, wastage, sale...">
            </div>
          </div>
          <div class="modal-actions">
            <button type="button" class="btn btn-ghost" id="adj-cancel">Cancel</button>
            <button type="submit" class="btn btn-primary">Review &rarr;</button>
          </div>
        </form>
      </div>

      <!-- STEP 2: CONFIRMATION -->
      <div id="adj-step-confirm" style="display:none;">
        <div class="confirm-title">Confirm Adjustment</div>
        <div class="confirm-rows">
          <div class="confirm-row"><span>Item</span><b id="cf-item"></b></div>
          <div class="confirm-row"><span>Action</span><b id="cf-action"></b></div>
          <div class="confirm-row"><span>Quantity</span><b id="cf-qty"></b></div>
          <div class="confirm-row"><span>Note</span><b id="cf-note"></b></div>
          <div class="confirm-row big"><span>Stock Level</span><b id="cf-stock"></b></div>
        </div>
        <div class="confirm-warn" id="cf-warn"></div>
        <div class="modal-actions">
          <button type="button" class="btn btn-ghost" id="cf-back">&larr; Go Back</button>
          <button type="button" class="btn btn-primary" id="cf-confirm">Confirm</button>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
const $ = id => document.getElementById(id);

let items = [];
let stats = null;
let editId = null;
let adjustId = null;
let adjustDir = 1;
let pendingAdjustQty = 0;
let sortKey = null;
let sortDir = 1;

function peso(n) {
  return "\u20B1" + Number(n).toLocaleString("en-PH", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function escapeHtml(str) {
  const d = document.createElement("div");
  d.textContent = str ?? "";
  return d.innerHTML;
}

function showToast(msg) {
  const t = $("toast");
  t.textContent = msg;
  t.classList.add("show");
  setTimeout(() => t.classList.remove("show"), 2200);
}

async function api(action, payload, method) {
  const opts = method === "POST"
    ? { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify(payload || {}) }
    : {};
  const res = await fetch(`api.php?action=${action}`, opts);
  const data = await res.json();
  if (data.auth === false) { showLogin(); throw new Error("Session expired"); }
  if (!data.ok) throw new Error(data.error || "Something went wrong");
  return data;
}

/* ---------- AUTH ---------- */
function showLogin() {
  $("login-view").style.display = "flex";
  $("app-view").style.display = "none";
}

function showApp(user) {
  $("login-view").style.display = "none";
  $("app-view").style.display = "block";
  $("u-name").textContent = user.name;
  $("u-role").textContent = user.username + " \u00B7 " + user.role;
  loadItems();
  loadHistory();
  loadSummary();
}

$("login-form").addEventListener("submit", async e => {
  e.preventDefault();
  const errBox = $("login-error");
  errBox.style.display = "none";
  try {
    const data = await api("login", { username: $("l-user").value.trim(), password: $("l-pass").value }, "POST");
    showApp(data.user);
    $("l-pass").value = "";
  } catch (err) {
    errBox.textContent = err.message.toUpperCase();
    errBox.style.display = "block";
  }
});

$("btn-logout").addEventListener("click", async () => {
  try { await api("logout", {}, "POST"); } catch {}
  showLogin();
});

/* ---------- LOAD + RENDER ---------- */
async function loadItems() {
  const search = $("search").value.trim();
  const cat = $("filter-cat").value;
  try {
    const data = await api(`list&search=${encodeURIComponent(search)}&category=${encodeURIComponent(cat)}`);
    items = data.items;
    stats = data.stats;
    render();
    renderChart();
  } catch (err) {
    const es = $("empty-state");
    es.style.display = "block";
    es.innerHTML = `<div class="icon">&#9888;</div>${escapeHtml(err.message || "Failed to load")}`;
  }
}

let histPeriod = "all";
let histPage = 1;

async function loadHistory() {
  try {
    const data = await api(`history&period=${histPeriod}&page=${histPage}`);
    histPage = data.page;
    renderHistory(data.movements, data);
  } catch {}
}

function getSorted(list) {
  if (!sortKey) return list;
  return [...list].sort((a, b) => {
    let va = a[sortKey], vb = b[sortKey];
    if (sortKey === "price" || sortKey === "qty") { va = Number(va); vb = Number(vb); }
    else { va = String(va).toLowerCase(); vb = String(vb).toLowerCase(); }
    if (va < vb) return -1 * sortDir;
    if (va > vb) return 1 * sortDir;
    return 0;
  });
}

function render() {
  const body = $("table-body");
  body.innerHTML = "";
  const list = getSorted(items);

  document.querySelectorAll("th.sortable").forEach(th => {
    th.querySelector(".arrow").textContent =
      th.dataset.sort === sortKey ? (sortDir === 1 ? " \u25B2" : " \u25BC") : "";
  });

  list.forEach(item => {
    let cls, label;
    if (Number(item.qty) <= 0) { cls = "st-out"; label = "\u2715 Out of stock"; }
    else if (Number(item.qty) <= Number(item.min_qty)) { cls = "st-low"; label = "\u25B2 Low stock"; }
    else { cls = "st-ok"; label = "In stock"; }

    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td class="item-name">${escapeHtml(item.name)}</td>
      <td>${escapeHtml(item.category)}</td>
      <td class="mono-num">${peso(item.price)}</td>
      <td class="mono-num ${Number(item.qty) <= Number(item.min_qty) ? "qty-low" : "qty-ok"}">${item.qty}</td>
      <td><span class="status ${cls}">${label}</span></td>
      <td class="actions-cell">
        <span class="row-actions">
          <button class="btn btn-icon" title="Stock In" onclick="openAdjust(${item.id}, 1)">+</button>
          <button class="btn btn-icon" title="Stock Out" onclick="openAdjust(${item.id}, -1)">&minus;</button>
          <button class="btn btn-sm btn-ghost" onclick="openEdit(${item.id})">Edit</button>
          <button class="btn btn-sm btn-danger" onclick="deleteItem(${item.id})">Del</button>
        </span>
      </td>`;
    body.appendChild(tr);
  });

  const es = $("empty-state");
  es.style.display = list.length ? "none" : "block";
  es.innerHTML = `<div class="icon">&#9635;</div>No items found. Click <strong>+ Add Item</strong> to start.`;

  $("stat-total").textContent = stats.total;
  $("stat-instock").textContent = stats.instock;
  $("stat-low").textContent = stats.low;
  $("stat-value").textContent = peso(stats.value);
}

function renderChart() {
  const wrap = $("chart-body");
  const cats = (stats && stats.by_category) || [];
  if (!cats.length) {
    wrap.innerHTML = `<div class="chart-empty">No data.</div>`;
    return;
  }
  const max = Math.max(...cats.map(c => c.units), 1);
  wrap.innerHTML = cats.map(c => `
    <div class="chart-row">
      <div class="chart-label" title="${escapeHtml(c.category)}">${escapeHtml(c.category)}</div>
      <div class="chart-track"><div class="chart-bar" style="width:${Math.round((c.units / max) * 100)}%"></div></div>
      <div class="chart-val">${c.units} units / ${c.items} sku</div>
    </div>`).join("");
}

function fmtDateTime(mysqlDt) {
  if (!mysqlDt) return "-";
  return mysqlDt.replace("T", " ").slice(0, 16);
}

function renderHistory(moves, meta) {
  const body = $("hist-body");
  body.innerHTML = "";
  moves.forEach(m => {
    const pos = m.change_qty > 0;
    const zero = Number(m.change_qty) === 0;
    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td class="mono-num">${fmtDateTime(m.created_at)}</td>
      <td class="item-name">${escapeHtml(m.item_name)}${zero ? ' <span class="status st-out">removed</span>' : ""}</td>
      <td class="${pos ? "hist-change-pos" : "hist-change-neg"}">${pos ? "+" : ""}${m.change_qty}</td>
      <td class="hist-note">${escapeHtml(m.note || "-")}</td>
      <td class="hist-user">@${escapeHtml(m.username)}</td>`;
    body.appendChild(tr);
  });
  $("hist-empty").style.display = moves.length ? "none" : "block";

  const sum = $("hist-summary");
  sum.style.display = "flex";
  sum.innerHTML = `
    <span>Period: <b>${escapeHtml(meta.period.toUpperCase())}</b></span>
    ${meta.since ? `<span>From: <b>${fmtDateTime(meta.since)}</b></span>` : ""}
    <span class="hs-in">Total IN: <b>+${meta.total_in}</b></span>
    <span>Total OUT: <b>&minus;${meta.total_out}</b></span>
    <span>Records: <b>${meta.count}</b></span>`;

  const pager = $("hist-pager");
  pager.classList.toggle("show", meta.pages > 1);
  $("hist-page-info").textContent = `Page ${meta.page} / ${meta.pages}`;
  $("hist-prev").toggleAttribute("disabled", meta.page <= 1);
  $("hist-next").toggleAttribute("disabled", meta.page >= meta.pages);
}

document.querySelectorAll("#hist-period button").forEach(b => {
  b.addEventListener("click", () => {
    document.querySelectorAll("#hist-period button").forEach(x => x.classList.remove("active"));
    b.classList.add("active");
    histPeriod = b.dataset.p;
    histPage = 1;
    loadHistory();
  });
});
$("hist-prev").addEventListener("click", () => { if (histPage > 1) { histPage--; loadHistory(); } });
$("hist-next").addEventListener("click", () => { histPage++; loadHistory(); });

/* ---------- MOVEMENT SUMMARY ---------- */
async function loadSummary() {
  try {
    const data = await api("summary");
    const s = data.summary;
    for (const key of ["week", "month", "year", "all"]) {
      $(`mv-${key}-in`).textContent = "+" + s[key].in;
      $(`mv-${key}-out`).textContent = "\u2212" + s[key].out;
    }
  } catch {}
}

/* ---------- ADD / EDIT ---------- */
function openModal(title) {
  $("modal-title").textContent = title;
  $("modal-overlay").classList.add("open");
  $("f-name").focus();
}

function closeModal() {
  $("modal-overlay").classList.remove("open");
  $("item-form").reset();
  $("f-min").value = 5;
  editId = null;
}

window.openEdit = function(id) {
  const item = items.find(i => i.id === id);
  if (!item) return;
  editId = item.id;
  $("f-name").value = item.name;
  $("f-category").value = item.category;
  $("f-unit-price").value = item.price;
  $("f-qty").value = item.qty;
  $("f-min").value = item.min_qty;
  openModal("Edit Item");
};

window.deleteItem = async function(id) {
  const item = items.find(i => i.id === id);
  if (!confirm(`Delete "${item ? item.name : "this item"}" from inventory?`)) return;
  await api("delete", { id }, "POST");
  showToast("Item deleted");
  loadItems();
  loadHistory();
  loadSummary();
};

$("btn-add").addEventListener("click", () => openModal("Add Item"));
$("btn-cancel").addEventListener("click", closeModal);
$("btn-x").addEventListener("click", closeModal);
$("modal-overlay").addEventListener("click", e => {
  if (e.target === $("modal-overlay")) closeModal();
});

$("item-form").addEventListener("submit", async e => {
  e.preventDefault();
  const payload = {
    name: $("f-name").value.trim(),
    category: $("f-category").value,
    price: parseFloat($("f-unit-price").value) || 0,
    qty: parseInt($("f-qty").value) || 0,
    min: parseInt($("f-min").value) || 0
  };
  const name = payload.name;
  if (!name) {
    alert("Item name is required");
    return;
  }
  // Client-side duplicate check (server also validates)
  const norm = s => s.trim().replace(/\s+/g, " ").toLowerCase();
  if (items.some(i => norm(i.name) === norm(name) && i.id !== editId)) {
    alert(`"${name}" already exists in inventory`);
    return;
  }
  try {
    if (editId) {
      payload.id = editId;
      await api("update", payload, "POST");
      showToast("Item updated");
    } else {
      await api("add", payload, "POST");
      showToast("Item added");
    }
    closeModal();
    loadItems();
    loadHistory();
    loadSummary();
  } catch (err) {
    alert(err.message);
  }
});

/* ---------- ADJUST STOCK (with confirmation) ---------- */
function setAdjStep(step) {
  $("adj-step-form").style.display = step === "form" ? "block" : "none";
  $("adj-step-confirm").style.display = step === "confirm" ? "block" : "none";
}

function setAdjDir(dir) {
  adjustDir = dir;
  $("seg-in").classList.toggle("active", dir === 1);
  $("seg-out").classList.toggle("active", dir === -1);
}

window.openAdjust = function(id, dir) {
  const item = items.find(i => i.id === id);
  if (!item) return;
  adjustId = id;
  $("adj-item-name").textContent = item.name;
  $("adj-current").value = item.qty;
  $("adj-qty").value = 1;
  $("adj-note").value = "";
  setAdjDir(dir ?? 1);
  setAdjStep("form");
  $("adjust-overlay").classList.add("open");
  $("adj-qty").focus();
};

function closeAdjust() {
  $("adjust-overlay").classList.remove("open");
  adjustId = null;
  pendingAdjustQty = 0;
  setAdjStep("form");
}

$("seg-in").addEventListener("click", () => setAdjDir(1));
$("seg-out").addEventListener("click", () => setAdjDir(-1));
$("adj-cancel").addEventListener("click", closeAdjust);
$("adj-x").addEventListener("click", closeAdjust);
$("adjust-overlay").addEventListener("click", e => {
  if (e.target === $("adjust-overlay")) closeAdjust();
});

$("adjust-form").addEventListener("submit", e => {
  e.preventDefault();
  const qty = parseInt($("adj-qty").value) || 0;
  if (qty <= 0) return;
  const item = items.find(i => i.id === adjustId);
  if (!item) return;
  const cur = Number(item.qty);
  const projected = adjustDir === 1 ? cur + qty : Math.max(0, cur - qty);

  pendingAdjustQty = qty;
  $("cf-item").textContent = item.name;
  $("cf-action").textContent = adjustDir === 1 ? "+ STOCK IN" : "\u2212 STOCK OUT";
  $("cf-qty").textContent = `${adjustDir > 0 ? "+" : "\u2212"}${qty}`;
  $("cf-note").textContent = $("adj-note").value.trim() || "-";
  $("cf-stock").textContent = `${cur}  \u2192  ${projected}`;

  const overdraw = adjustDir === -1 && qty > cur;
  $("cf-warn").style.display = overdraw ? "block" : "none";
  if (overdraw) $("cf-warn").textContent =
    `WARNING: Only ${cur} in stock. Stock out will be capped at ${cur}, leaving 0.`;

  setAdjStep("confirm");
});

$("cf-back").addEventListener("click", () => setAdjStep("form"));

$("cf-confirm").addEventListener("click", async () => {
  try {
    const data = await api("adjust", {
      id: adjustId,
      delta: pendingAdjustQty * adjustDir,
      note: $("adj-note").value.trim()
    }, "POST");
    showToast(`${data.applied > 0 ? "+" : ""}${data.applied} applied \u2014 new qty: ${data.new_qty}`);
    closeAdjust();
    loadItems();
    loadHistory();
    loadSummary();
  } catch (err) {
    alert(err.message);
    closeAdjust();
  }
});

/* ---------- MISC CONTROLS ---------- */
$("btn-export").addEventListener("click", () => {
  window.location.href = "api.php?action=export";
});
$("btn-hist-refresh").addEventListener("click", loadHistory);

document.addEventListener("keydown", e => {
  if (e.key === "Escape") { closeModal(); closeAdjust(); }
});

document.querySelectorAll("th.sortable").forEach(th => {
  th.addEventListener("click", () => {
    const key = th.dataset.sort;
    if (sortKey === key) sortDir *= -1;
    else { sortKey = key; sortDir = 1; }
    render();
  });
});

let debounceTimer;
$("search").addEventListener("input", () => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(loadItems, 250);
});
$("filter-cat").addEventListener("change", loadItems);

/* ---------- BOOT ---------- */
(async function boot() {
  try {
    const data = await api("me");
    if (data.user) showApp(data.user);
    else showLogin();
  } catch {
    showLogin();
  }
})();
</script>

</body>
</html>
