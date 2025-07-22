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
        $total = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE student_id = $sid AND class_id = $class_id")->fetch_assoc()['c'];
        $present = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE student_id = $sid AND class_id = $class_id AND status='present'")->fetch_assoc()['c'];
        $absent = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE student_id = $sid AND class_id = $class_id AND status='absent'")->fetch_assoc()['c'];
        $late = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE student_id = $sid AND class_id = $class_id AND status='late'")->fetch_assoc()['c'];
        $data[] = [
            'name' => $stu['first_name'].' '.$stu['last_name'],
            'total' => $total, 'present' => $present, 'absent' => $absent, 'late' => $late
        ];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Attendance Summary</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Attendance Summary</h2>
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
                <th>Name</th><th>Total</th><th>Present</th><th>Absent</th><th>Late</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= $row['total'] ?></td>
                <td><?= $row['present'] ?></td>
                <td><?= $row['absent'] ?></td>
                <td><?= $row['late'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</body>
</html>