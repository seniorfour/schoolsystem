<?php
require_once('../db.php');

// Fetch dropdown data
$students = $conn->query("SELECT student_id, first_name, last_name FROM students ORDER BY first_name");
$subjects = $conn->query("SELECT subject_id, name FROM subjects ORDER BY name");
$classes = $conn->query("SELECT class_id, name, year FROM classes ORDER BY year DESC, name");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Enter Student Marks</title>
  <link rel="stylesheet" href="../assets/bootstrap.min.css">
  <link ref="stylesheet" href="../assets/css/style.css">
  <style>
	body {
		font-family:times new roman;
		margin-left:20px;
		color:black;
	}
	form {
		border:1px;
	}
  </style>
</head>
<body class="container mt-4">
  <h2>Enter Student Marks</h2>
  <form action="save_marks.php" method="POST">
    <div class="mb-3">
      <label>Student:</label>
      <select name="student_id" class="form-select" required>
        <?php while($s = $students->fetch_assoc()): ?>
          <option value="<?= $s['student_id'] ?>">
            <?= $s['first_name'] . ' ' . $s['last_name'] ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label>Subject:</label>
      <select name="subject_id" class="form-select" required>
        <?php while($sub = $subjects->fetch_assoc()): ?>
          <option value="<?= $sub['subject_id'] ?>"><?= $sub['name'] ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label>Class:</label>
      <select name="class_id" class="form-select" required>
        <?php while($c = $classes->fetch_assoc()): ?>
          <option value="<?= $c['class_id'] ?>">
            <?= $c['name'] . ' - ' . $c['year'] ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label>Term:</label>
      <input type="text" name="term" class="form-control" placeholder="e.g., Term 1" required>
    </div>

    <div class="mb-3">
      <label>Year:</label>
      <input type="number" name="year" class="form-control" value="<?= date('Y') ?>" required>
    </div>

    <div class="mb-3">
      <label>Marks Obtained:</label>
      <input type="number" name="marks_obtained" step="0.01" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Maximum Marks:</label>
      <input type="number" name="max_marks" step="0.01" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Exam Date:</label>
      <input type="date" name="exam_date" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Save Marks</button>
  </form>
</body>
</html>
