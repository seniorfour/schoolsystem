<?php
require_once('../db.php');
$classes = $conn->query("SELECT class_id, name, year FROM classes ORDER BY year, name");
$class_id = $_GET['class_id'] ?? '';
$data = [];

if ($class_id) {
    $students = $conn->query("SELECT student_id, first_name, last_name FROM students s
        JOIN enrollments e ON s.student_id = e.student_id WHERE e.class_id = $class_id");
    while ($stu = $students->fetch_assoc()) {
        $sid = $stu['student_id'];
        $grades = $conn->query("SELECT AVG(marks) as avg_marks FROM grades WHERE student_id = $sid")->fetch_assoc()['avg_marks'];
        $data[] = [
            'name' => $stu['first_name'].' '.$stu['last_name'],
            'average' => $grades ? round($grades, 2) : 0
        ];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Performance Reports</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Performance Reports</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">&larr; Back to Dashboard</a>
    <form method="get" class="row g-3 mb-4">
        <div class="col-md-6">
            <label class="form-label">Class</label>
            <select name="class_id" class="form-select" required>
                <option value="">-- Select Class --</option>
                <?php while($c = $classes->fetch_assoc()): ?>
                <option value="<?= $c['class_id'] ?>" <?= $c['class_id']==$class_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['name'].' ('.$c['year'].')') ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-success">Show</button>
        </div>
    </form>
    <?php if ($class_id): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th><th>Average Marks</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= $row['average'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</body>
</html>