<?php
require_once 'db.php';

$msg     = '';
$msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    } elseif (!is_numeric($salary) || !is_numeric($department_id)) {
        $msg     = 'Salary and Department ID must be numbers.';
        $msgType = 'error';
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO employees (emp_name, job_name, salary, hire_date, department_id, department_name)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('ssdsds', $emp_name, $job_name, $salary, $hire_date, $department_id, $department_name);

        if ($stmt->execute()) {
            $msg     = 'Employee added successfully! ID: ' . $conn->insert_id;
            $msgType = 'success';
        } else {
            $msg     = 'Insert failed: ' . htmlspecialchars($stmt->error);
            $msgType = 'error';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Employee — CW-06</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="demo-page">
  <div class="demo-shell">

    <div class="demo-card">
      <h1 class="demo-title">Add Employee</h1>
      <p class="demo-subtitle">Fill out the form below to insert a new record into the database.</p>

      <form method="POST" action="employee_demo.php" novalidate>
        <div class="demo-grid">

          <div class="demo-field">
            <label for="emp_name">Full Name</label>
            <input class="demo-input" type="text" id="emp_name" name="emp_name"
                   placeholder="e.g. Ana Lopez"
                   value="<?= htmlspecialchars($_POST['emp_name'] ?? '') ?>" required>
          </div>

          <div class="demo-field">
            <label for="job_name">Job Title</label>
            <input class="demo-input" type="text" id="job_name" name="job_name"
                   placeholder="e.g. Developer"
                   value="<?= htmlspecialchars($_POST['job_name'] ?? '') ?>" required>
          </div>

          <div class="demo-field">
            <label for="salary">Salary ($)</label>
            <input class="demo-input" type="number" id="salary" name="salary"
                   placeholder="e.g. 73000"
                   min="0" step="0.01"
                   value="<?= htmlspecialchars($_POST['salary'] ?? '') ?>" required>
          </div>

          <div class="demo-field">
            <label for="hire_date">Hire Date</label>
            <input class="demo-input" type="date" id="hire_date" name="hire_date"
                   value="<?= htmlspecialchars($_POST['hire_date'] ?? '') ?>" required>
          </div>

          <div class="demo-field">
            <label for="department_id">Department ID</label>
            <input class="demo-input" type="number" id="department_id" name="department_id"
                   placeholder="e.g. 1"
                   min="1"
                   value="<?= htmlspecialchars($_POST['department_id'] ?? '') ?>" required>
          </div>

          <div class="demo-field">
            <label for="department_name">Department Name</label>
            <input class="demo-input" type="text" id="department_name" name="department_name"
                   placeholder="e.g. Engineering"
                   value="<?= htmlspecialchars($_POST['department_name'] ?? '') ?>" required>
          </div>

        </div>

        <div class="demo-actions">
          <button class="demo-btn" type="submit">Add Employee</button>
          <a class="demo-link" href="read_employees.php">View All Records &rarr;</a>
        </div>
      </form>

      <?php if ($msg !== ''): ?>
        <div class="demo-msg <?= $msgType ?>">
          <?= htmlspecialchars($msg) ?>
        </div>
      <?php endif; ?>
    </div>

  </div>
</body>
</html>
