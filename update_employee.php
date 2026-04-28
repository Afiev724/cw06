<?php
require_once 'db.php';

$msg     = '';
$msgType = '';
$row     = null;

// Load record into form
if (isset($_GET['emp_id'])) {
    $id   = (int)$_GET['emp_id'];
    $stmt = $conn->prepare("SELECT * FROM employees WHERE emp_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$row) {
        $msg     = 'Employee not found.';
        $msgType = 'error';
    }
}

// Process update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id              = (int)$_POST['emp_id'];
    $emp_name        = trim($_POST['emp_name']        ?? '');
    $job_name        = trim($_POST['job_name']        ?? '');
    $salary          = trim($_POST['salary']          ?? '');
    $hire_date       = trim($_POST['hire_date']       ?? '');
    $department_id   = trim($_POST['department_id']   ?? '');
    $department_name = trim($_POST['department_name'] ?? '');

    if ($emp_name === '' || $job_name === '' || $salary === '' ||
        $hire_date === '' || $department_id === '' || $department_name === '') {
        $msg     = 'All fields are required.';
        $msgType = 'error';
        $row     = $_POST; // repopulate form
    } elseif (!is_numeric($salary) || !is_numeric($department_id)) {
        $msg     = 'Salary and Department ID must be numbers.';
        $msgType = 'error';
        $row     = $_POST;
    } else {
        $stmt = $conn->prepare(
            "UPDATE employees
             SET emp_name=?, job_name=?, salary=?, hire_date=?, department_id=?, department_name=?
             WHERE emp_id=?"
        );
        $dept_id = (int)$department_id;
        $stmt->bind_param('ssdsdsi', $emp_name, $job_name, $salary, $hire_date, $dept_id, $department_name, $id);
        if ($stmt->execute()) {
            $msg     = 'Employee updated successfully.';
            $msgType = 'success';
            $row     = null; // hide form after success
        } else {
            $msg     = 'Update failed: ' . htmlspecialchars($stmt->error);
            $msgType = 'error';
            $row     = $_POST;
        }
        $stmt->close();
    }
}

// Always fetch table
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
  <title>Update Employee — CW-06</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="demo-page">
  <div class="demo-shell">

    <!-- Edit Form -->
    <?php if ($row): ?>
    <div class="demo-card" style="margin-bottom:1.25rem;">
      <h1 class="demo-title">Edit Employee</h1>
      <p class="demo-subtitle">Update the fields and save.</p>

      <form method="POST" action="update.php" novalidate>
        <input type="hidden" name="emp_id" value="<?= (int)$row['emp_id'] ?>">
        <div class="demo-grid">
          <div class="demo-field">
            <label for="emp_name">Full Name</label>
            <input class="demo-input" type="text" id="emp_name" name="emp_name"
                   value="<?= htmlspecialchars($row['emp_name']) ?>" required>
          </div>
          <div class="demo-field">
            <label for="job_name">Job Title</label>
            <input class="demo-input" type="text" id="job_name" name="job_name"
                   value="<?= htmlspecialchars($row['job_name']) ?>" required>
          </div>
          <div class="demo-field">
            <label for="salary">Salary ($)</label>
            <input class="demo-input" type="number" id="salary" name="salary"
                   min="0" step="0.01"
                   value="<?= htmlspecialchars($row['salary']) ?>" required>
          </div>
          <div class="demo-field">
            <label for="hire_date">Hire Date</label>
            <input class="demo-input" type="date" id="hire_date" name="hire_date"
                   value="<?= htmlspecialchars($row['hire_date']) ?>" required>
          </div>
          <div class="demo-field">
            <label for="department_id">Department ID</label>
            <input class="demo-input" type="number" id="department_id" name="department_id"
                   min="1"
                   value="<?= htmlspecialchars($row['department_id']) ?>" required>
          </div>
          <div class="demo-field">
            <label for="department_name">Department Name</label>
            <input class="demo-input" type="text" id="department_name" name="department_name"
                   value="<?= htmlspecialchars($row['department_name']) ?>" required>
          </div>
        </div>
        <div class="demo-actions">
          <button class="demo-btn" type="submit">Save Changes</button>
          <a class="demo-link" href="update.php">Cancel</a>
        </div>
      </form>
    </div>
    <?php endif; ?>

    <!-- Records Table -->
    <div class="demo-card">
      <h1 class="demo-title">Select Employee to Update</h1>
      <p class="demo-subtitle">Click Edit on any row to load the update form above.</p>

      <div class="demo-actions" style="margin-top:0; margin-bottom:.75rem;">
        <a class="demo-link" href="employee_demo.php">&larr; Add Employee</a>
        &nbsp;&nbsp;
        <a class="demo-link" href="read_employees.php">View Records</a>
        &nbsp;&nbsp;
        <a class="demo-link" href="delete.php">Delete Page</a>
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
                  <a class="demo-link" href="update.php?emp_id=<?= (int)$r['emp_id'] ?>">Edit</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
        <p style="margin-top:.6rem;font-size:.82rem;color:var(--demo-muted);">
          <?= $result->num_rows ?> record<?= $result->num_rows !== 1 ? 's' : '' ?> found.
        </p>
      <?php else: ?>
        <div class="demo-msg error">No records found. <a class="demo-link" href="employee_demo.php">Add one now</a>.</div>
      <?php endif; ?>
    </div>

  </div>
</body>
</html>
