<?php
require_once 'db.php';

$msg     = '';
$msgType = '';

// Process delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['emp_id'])) {
    $id   = (int)$_POST['emp_id'];
    $stmt = $conn->prepare("DELETE FROM employees WHERE emp_id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        $msg     = 'Employee deleted successfully.';
        $msgType = 'success';
    } else {
        $msg     = 'Delete failed: ' . htmlspecialchars($stmt->error);
        $msgType = 'error';
    }
    $stmt->close();
}

// Fetch remaining records
$result = $conn->query(
    "SELECT emp_id, emp_name, job_name, salary, hire_date, department_name
     FROM employees ORDER BY emp_id DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete Employee — CW-06</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="demo-page">
  <div class="demo-shell">
    <div class="demo-card">
      <h1 class="demo-title">Delete Employee</h1>
      <p class="demo-subtitle">Click Delete on a row to permanently remove that record.</p>

      <div class="demo-actions" style="margin-top:0; margin-bottom:.75rem;">
        <a class="demo-link" href="employee_demo.php">&larr; Add Employee</a>
        &nbsp;&nbsp;
        <a class="demo-link" href="read_employees.php">View Records</a>
        &nbsp;&nbsp;
        <a class="demo-link" href="update.php">Update Page</a>
      </div>

      <?php if ($msg !== ''): ?>
        <div class="demo-msg <?= $msgType ?>"><?= htmlspecialchars($msg) ?></div>
      <?php endif; ?>

      <?php if ($result && $result->num_rows > 0): ?>
        <table class="demo-table">
          <thead>
            <tr>
              <th>ID</th><th>Name</th><th>Job Title</th>
              <th>Salary</th><th>Hire Date</th><th>Department</th><th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($r = $result->fetch_assoc()): ?>
              <tr>
                <td><?= (int)$r['emp_id'] ?></td>
                <td><?= htmlspecialchars($r['emp_name']) ?></td>
                <td><?= htmlspecialchars($r['job_name']) ?></td>
                <td>$<?= number_format((float)$r['salary'], 2) ?></td>
                <td><?= htmlspecialchars($r['hire_date']) ?></td>
                <td><span class="demo-badge"><?= htmlspecialchars($r['department_name']) ?></span></td>
                <td>
                  <form method="POST" action="delete.php" style="display:inline;"
                        onsubmit="return confirm('Delete <?= htmlspecialchars(addslashes($r['emp_name'])) ?>? This cannot be undone.');">
                    <input type="hidden" name="emp_id" value="<?= (int)$r['emp_id'] ?>">
                    <button type="submit"
                            style="background:none;border:none;cursor:pointer;
                                   color:var(--demo-danger);font-weight:600;
                                   font-family:inherit;font-size:inherit;padding:0;">
                      Delete
                    </button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
        <p style="margin-top:.6rem;font-size:.82rem;color:var(--demo-muted);">
          <?= $result->num_rows ?> record<?= $result->num_rows !== 1 ? 's' : '' ?> found.
        </p>
      <?php else: ?>
        <div class="demo-msg error">No records to delete. <a class="demo-link" href="employee_demo.php">Add one now</a>.</div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
