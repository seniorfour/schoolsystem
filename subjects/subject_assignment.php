<?php
require_once('../db.php');

// Handle class filter
$class_id = $_GET['class_id'] ?? '';

// Fetch classes for dropdown
$classes = $conn->query("SELECT class_id, name FROM classes ORDER BY name");

// Fetch subjects
$subjects = $conn->query("SELECT subject_id, code FROM subjects ORDER BY code");

// Fetch students in selected class
$students = [];
if ($class_id) {
    $stmt = $conn->prepare("SELECT student_id, first_name, last_name FROM students WHERE class_id = ?");
    $stmt->bind_param('i', $class_id);
    $stmt->execute();
    $students = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Fetch existing subject assignments
$assigned = [];
if ($class_id) {
    $result = $conn->query("SELECT student_id, subject_id FROM student_subjects");
    while ($row = $result->fetch_assoc()) {
        $assigned[$row['student_id']][$row['subject_id']] = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attach Subjects to Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        th, td { text-align: center; vertical-align: middle; }
        .table-responsive { overflow-x: auto; }
    </style>
</head>
<body class="p-4">

<div class="container">
    <h4 class="mb-4">Attach Subjects to Students</h4>
    <form method="get" class="row mb-4">
        <div class="col-md-4">
            <label for="class_id" class="form-label">Class *</label>
            <select name="class_id" id="class_id" class="form-select" required>
				<option value="">-- Select Class --</option>
				<?php while ($row = $classes->fetch_assoc()): ?>
					<option value="<?= $row['class_id'] ?>" <?= $class_id == $row['class_id'] ? 'selected' : '' ?>>
						<?= htmlspecialchars($row['name']) ?>
					</option>
				<?php endwhile; ?>
			</select>
        </div>
        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
    </form>

    <?php if ($class_id && count($students) > 0): ?>
        <h5 class="mb-3">ATTACH SUBJECTS TO STUDENTS</h5>
        <form method="post" action="save_subject_assignments.php">
            <input type="hidden" name="class_id" value="<?= $class_id ?>">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <?php foreach ($subjects as $subject): ?>
                                <th><?= htmlspecialchars($subject['code']) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $i => $student): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
                                <?php foreach ($subjects as $subject): ?>
                                    <td>
                                        <input type="checkbox" name="subjects[<?= $student['student_id'] ?>][]" value="<?= $subject['subject_id'] ?>"
                                            <?= isset($assigned[$student['student_id']][$subject['subject_id']]) ? 'checked' : '' ?>>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-success mt-3">Save Assignments</button>
        </form>
    <?php elseif ($class_id): ?>
        <div class="alert alert-warning">No students found in this class.</div>
    <?php endif; ?>
</div>

</body>
</html>
