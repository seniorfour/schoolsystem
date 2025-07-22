<?php
require_once('db.php');

// Get class_id, term, and year from filters or default
$class_id = $_GET['class_id'] ?? '';
$term = $_GET['term'] ?? '';
$year = $_GET['year'] ?? '';

// Step 1: Get all subjects (using subject codes)
$subjects = [];
$subject_sql = $conn->query("SELECT subject_id, code FROM subjects ORDER BY code");
while ($row = $subject_sql->fetch_assoc()) {
    $subjects[$row['subject_id']] = $row['code'];
}

// Step 2: Get all students in selected class and term/year (if filters provided)
$students_query = "
SELECT DISTINCT s.student_id, s.first_name, s.last_name, c.name AS class_name
FROM student_marks sm
JOIN students s ON sm.student_id = s.student_id
JOIN classes c ON sm.class_id = c.class_id
WHERE 1
";
$params = [];
$types = '';

if (!empty($class_id)) {
    $students_query .= " AND sm.class_id = ?";
    $params[] = $class_id;
    $types .= 'i';
}
if (!empty($term)) {
    $students_query .= " AND sm.term = ?";
    $params[] = $term;
    $types .= 's';
}
if (!empty($year)) {
    $students_query .= " AND sm.year = ?";
    $params[] = $year;
    $types .= 's';
}

$stmt = $conn->prepare($students_query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$students_result = $stmt->get_result();

// Step 3: Build marks data indexed by student and subject
$marks_map = [];
$marks_sql = "
SELECT student_id, subject_id, marks_obtained
FROM student_marks
WHERE 1
";
if (!empty($class_id)) {
    $marks_sql .= " AND class_id = " . intval($class_id);
}
if (!empty($term)) {
    $marks_sql .= " AND term = '" . $conn->real_escape_string($term) . "'";
}
if (!empty($year)) {
    $marks_sql .= " AND year = '" . $conn->real_escape_string($year) . "'";
}

$marks_result = $conn->query($marks_sql);
while ($row = $marks_result->fetch_assoc()) {
    $marks_map[$row['student_id']][$row['subject_id']] = rtrim(rtrim(number_format($row['marks_obtained'], 2), '0'), '.');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Marksheet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body { padding: 20px; }
        table { font-size: 14px; border: 1px solid #ccc; }
        th, td { border: 1px solid #ccc; text-align: center; vertical-align: middle; }
        th { background-color: #f8f9fa; }
    </style>
</head>
<body>

<h4 class="mb-4">Student Marksheet (Per Subject - by Subject Code)</h4>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th style="text-align:left">Student Name</th>
            <th>Class</th>
            <?php foreach ($subjects as $subcode): ?>
                <th><?= htmlspecialchars($subcode) ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $sn = 1;
        while ($student = $students_result->fetch_assoc()):
            $sid = $student['student_id'];
        ?>
        <tr>
            <td><?= $sn++ ?></td>
            <td style="text-align:left"><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
            <td><?= htmlspecialchars($student['class_name']) ?></td>
            <?php foreach ($subjects as $sub_id => $subcode): ?>
                <td><?= $marks_map[$sid][$sub_id] ?? '-' ?></td>
            <?php endforeach; ?>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>

</body>
</html>
