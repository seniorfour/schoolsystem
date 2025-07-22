<?php
require_once('../db.php');

// Get students for dropdown
$students = $conn->query("SELECT student_id, first_name, last_name FROM students ORDER BY first_name, last_name");
$student_id = $_GET['student_id'] ?? '';

$report = null;
if ($student_id) {
    $query = "SELECT g.*, e.name AS exam_name, e.date, e.term, s.name AS subject_name, c.name AS class_name, c.year AS class_year
              FROM grades g
              JOIN exams e ON g.exam_id = e.exam_id
              JOIN subjects s ON e.subject_id = s.subject_id
              JOIN classes c ON e.class_id = c.class_id
              WHERE g.student_id = ?
              ORDER BY e.date DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $student_id);
    $stmt->execute();
    $report = $stmt->get_result();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Grade Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Student Grade Report</h2>
    <form method="get" class="row g-3 mb-4">
        <div class="col-md-6">
            <label class="form-label">Student</label>
            <select name="student_id" class="form-select" required>
                <option value="">-- Select Student --</option>
                <?php while($s = $students->fetch_assoc()): ?>
                <option value="<?= $s['student_id'] ?>" <?= $s['student_id']==$student_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['first_name'].' '.$s['last_name']) ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-success">View Report</button>
        </div>
    </form>
    <?php if ($report): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th><th>Exam</th><th>Class</th><th>Subject</th><th>Term</th><th>Marks</th><th>Grade</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($report->num_rows == 0): ?>
                <tr><td colspan="7">No grades found for this student.</td></tr>
            <?php else: while($row = $report->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= htmlspecialchars($row['exam_name']) ?></td>
                    <td><?= htmlspecialchars($row['class_name'].' ('.$row['class_year'].')') ?></td>
                    <td><?= htmlspecialchars($row['subject_name']) ?></td>
                    <td><?= htmlspecialchars($row['term']) ?></td>
                    <td><?= htmlspecialchars($row['marks']) ?></td>
                    <td><?= htmlspecialchars($row['grade']) ?></td>
                </tr>
            <?php endwhile; endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>