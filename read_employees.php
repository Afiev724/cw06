<?php
require_once 'db.php';

$result = $conn->query(
    "SELECT emp_id, emp_name, job_name, salary, hire_date, department_name
     FROM employees
     ORDER BY emp_id DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Records — CW-06</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="demo-page">
  <div class="demo-shell">

    <div class="demo-card">
      <h1 class="demo-title">Employee Records</h1>
      <p class="demo-subtitle">All rows currently stored in the <code>employees</code> table.</p>

      <div class="demo-actions" style="margin-top:0; margin-bottom:.75rem;">
        <a class="demo-link" href="employee_demo.php">&larr; Add New Employee</a>
      </div>

      <?php if ($result && $result->num_rows > 0): ?>
        <table class="demo-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Job Title</th>
              <th>Salary</th>
              <th>Hire Date</th>
              <th>Department</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= (int)$row['emp_id'] ?></td>
                <td><?= htmlspecialchars($row['emp_name']) ?></td>
                <td><?= htmlspecialchars($row['job_name']) ?></td>
                <td>$<?= number_format((float)$row['salary'], 2) ?></td>
                <td><?= htmlspecialchars($row['hire_date']) ?></td>
                <td><span class="demo-badge"><?= htmlspecialchars($row['department_name']) ?></span></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
        <p style="margin-top:.6rem; font-size:.82rem; color:var(--demo-muted);">
          <?= $result->num_rows ?> record<?= $result->num_rows !== 1 ? 's' : '' ?> found.
        </p>
      <?php else: ?>
        <div class="demo-msg error">No records found. <a class="demo-link" href="employee_demo.php">Add one now</a>.</div>
      <?php endif; ?>
    </div>

  </div>
</body>
</html>
