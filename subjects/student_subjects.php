<?php
require_once('../db.php');

// Handle filter
$class_id = $_GET['class_id'] ?? '';

// Fetch classes and subjects
$classes = $conn->query("SELECT class_id, name FROM classes ORDER BY name");
$subjects = $conn->query("SELECT subject_id, code FROM subjects ORDER BY name");

// Fetch students in selected class
$students = [];
if ($class_id) {
    $stmt = $conn->prepare("SELECT * FROM students WHERE class_id = ?");
    $stmt->bind_param("i", $class_id);
    $stmt->execute();
    $students = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attach Subjects to Students</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
    <style>
        .subject-table th, .subject-table td {
            text-align: center;
            vertical-align: middle;
        }
        .sticky-top {
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
        }
    </style>
</head>
<body class="container mt-4">

<h4>Attach Subjects to Students</h4>

<form method="GET" class="row g-2 mb-4">
    <div class="col-md-4">
        <label>Class</label>
        <select name="class_id" class="form-select" required>
            <option value="">-- Select Class --</option>
            <?php while ($c = $classes->fetch_assoc()): ?>
                <option value="<?= $c['class_id'] ?>" <?= $c['class_id'] == $class_id ? 'selected' : '' ?>>
                    <?= $c['name'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary">Search</button>
    </div>
</form>

<?php if ($students && $students->num_rows > 0): ?>
    <form method="POST" action="save_assignments.php">
        <input type="hidden" name="class_id" value="<?= $class_id ?>">
        <table class="table table-bordered subject-table table-sm">
            <thead class="table-light sticky-top">
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <?php $subject_arr = []; while ($s = $subjects->fetch_assoc()): ?>
                        <?php $subject_arr[] = $s; ?>
                        <th><?= htmlspecialchars($s['code']) ?></th>
                    <?php endwhile; ?>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; while ($stu = $students->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($stu['first_name'] . ' ' . $stu['last_name']) ?></td>
                        <?php foreach ($subject_arr as $sub): ?>
                            <td>
                                <input type="checkbox"
                                    name="assignments[<?= $stu['student_id'] ?>][<?= $sub['subject_id'] ?>]"
                                    value="1">
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="text-end">
            <button type="submit" class="btn btn-success">Save Assignments</button>
        </div>
    </form>
<?php elseif ($class_id): ?>
    <div class="alert alert-warning">No students found in this class.</div>
<?php endif; ?>

</body>
</html>
