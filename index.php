<?php require_once 'db.php';
$count = $conn->query("SELECT COUNT(*) AS c FROM employees")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CW-06 — Employee Manager</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="demo-page">
  <div class="demo-shell">
    <div class="demo-card">
      <h1 class="demo-title">CW-06 — Employee Manager</h1>
      <p class="demo-subtitle">
        PHP + MySQL CRUD demo &nbsp;|&nbsp;
        Database: <strong>afievre2</strong> &nbsp;|&nbsp;
        Records: <strong><?= (int)$count ?></strong>
      </p>

      <nav style="display:grid; grid-template-columns: repeat(2, 1fr); gap:.75rem; margin-top:1rem;">

        <a href="employee_demo.php" style="text-decoration:none;">
          <div class="demo-card" style="padding:1rem; text-align:center;">
            <div style="font-weight:700; color:var(--demo-accent);">Add Employee</div>
            <div style="font-size:.82rem; color:var(--demo-muted); margin-top:.25rem;">
              Insert a new record via HTML form + prepared statement
            </div>
          </div>
        </a>

        <a href="read_employees.php" style="text-decoration:none;">
          <div class="demo-card" style="padding:1rem; text-align:center;">
            <div style="font-weight:700; color:var(--demo-accent);">View Records</div>
            <div style="font-size:.82rem; color:var(--demo-muted); margin-top:.25rem;">
              Display all employees in a styled table
            </div>
          </div>
        </a>

        <a href="update_employee.php" style="text-decoration:none;">
          <div class="demo-card" style="padding:1rem; text-align:center;">
            <div style="font-weight:700; color:var(--demo-accent);">Update Employee</div>
            <div style="font-size:.82rem; color:var(--demo-muted); margin-top:.25rem;">
              Edit any field with a pre-filled form
            </div>
          </div>
        </a>

        <a href="delete_employee.php" style="text-decoration:none;">
          <div class="demo-card" style="padding:1rem; text-align:center;">
            <div style="font-weight:700; color:var(--demo-danger);">Delete Employee</div>
            <div style="font-size:.82rem; color:var(--demo-muted); margin-top:.25rem;">
              Permanently remove a record with confirmation
            </div>
          </div>
        </a>

      </nav>

      <div style="margin-top:1rem; padding-top:.75rem; border-top:1px solid var(--demo-border);
                  font-size:.8rem; color:var(--demo-muted);">
        <a class="demo-link" href="test_conn.php">Test DB Connection</a>
      </div>
    </div>
  </div>
</body>
</html>
