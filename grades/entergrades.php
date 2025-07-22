<?php
require_once('../db.php');

// Get dropdown data
$classList = $conn->query("SELECT class_id, name FROM classes ORDER BY name");

$class_id = $_GET['class_id'] ?? '';
$exam_id = $_GET['exam_id'] ?? '';
$subject_id = $_GET['subject_id'] ?? '';
$search = $conn->real_escape_string($_GET['search'] ?? '');
$load = isset($_GET['load']);

// Load exam info
if ($exam_id) {
    $stmt = $conn->prepare("SELECT e.*, c.name AS class_name FROM exams e JOIN classes c ON e.class_id = c.class_id WHERE e.exam_id = ?");
    $stmt->bind_param('i', $exam_id);
    $stmt->execute();
    $exam = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$exam) die("Exam not found.");

    $year = date('Y', strtotime($exam['date']));
    $searchClause = ($search && $subject_id) ? "AND (s.first_name LIKE '%$search%' OR s.last_name LIKE '%$search%')" : '';

    if ($load && $subject_id) {
        $students = $conn->query("SELECT s.student_id, s.first_name, s.last_name 
            FROM enrollments e 
            JOIN students s ON e.student_id = s.student_id 
            WHERE e.class_id = '{$exam['class_id']}' AND e.academic_year = '$year' 
            $searchClause
            ORDER BY s.first_name, s.last_name");
    }
}

// Save marks
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['grades']) && isset($_POST['subject_id']) && isset($_POST['exam_id'])) {
    $subject_id = intval($_POST['subject_id']);
    $exam_id = intval($_POST['exam_id']);
    
    foreach ($_POST['grades'] as $student_id => $marks) {
        $student_id = intval($student_id);
        $marks = is_numeric($marks) ? floatval($marks) : null;

        // Delete existing entry
        $conn->query("DELETE FROM grades WHERE exam_id = '$exam_id' AND student_id = '$student_id' AND subject_id = '$subject_id'");

        // Insert new mark if valid
        if ($marks !== null && $marks <= 3) {
            $stmt = $conn->prepare("INSERT INTO grades (exam_id, student_id, subject_id, marks) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('iiid', $exam_id, $student_id, $subject_id, $marks);
            $stmt->execute();
            $stmt->close();
        }
    }

    header("Location: ../dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enter Grades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        th, td { text-align: center; vertical-align: middle; }
        .table-responsive { overflow-x: auto; }
    </style>
</head>
<body class="bg-light p-4">
<div class="container">
    <h4 class="text-primary mb-4">Enter Student Grades</h4>

    <!-- Filter Form -->
    <form method="get" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">Select Class</label>
            <select name="class_id" class="form-select" required>
                <option value="">-- Choose Class --</option>
                <?php while ($c = $classList->fetch_assoc()): ?>
                    <option value="<?= $c['class_id'] ?>" <?= $c['class_id'] == $class_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <?php if ($class_id): ?>
        <div class="col-md-3">
            <label class="form-label">Select Exam</label>
            <select name="exam_id" class="form-select" required>
                <option value="">-- Choose Exam --</option>
                <?php
                $examList = $conn->query("SELECT exam_id, name, date FROM exams WHERE class_id = '$class_id' ORDER BY date DESC");
                while ($e = $examList->fetch_assoc()):
                ?>
                    <option value="<?= $e['exam_id'] ?>" <?= $e['exam_id'] == $exam_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($e['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <?php endif; ?>

        <?php if ($exam_id): ?>
        <div class="col-md-3">
            <label class="form-label">Select Subject</label>
            <select name="subject_id" class="form-select" required>
                <option value="">-- Choose Subject --</option>
                <?php
                $subjectList = $conn->query("SELECT subject_id, name FROM subjects ORDER BY name");
                while ($s = $subjectList->fetch_assoc()):
                ?>
                    <option value="<?= $s['subject_id'] ?>" <?= $s['subject_id'] == $subject_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <?php endif; ?>

        <?php if ($class_id && $exam_id && $subject_id): ?>
        <div class="col-md-3">
            <label class="form-label">Search Student</label>
            <div class="input-group">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="First or Last Name">
                <button type="submit" name="load" value="1" class="btn btn-outline-primary">Search</button>
            </div>
        </div>

        <div class="col-md-3">
            <label class="form-label d-block invisible">Load</label>
            <button type="submit" name="load" value="1" class="btn btn-success w-100">Load Students</button>
        </div>
        <?php endif; ?>
    </form>

    <?php if (isset($students) && $students->num_rows > 0 && $subject_id): ?>
    <form method="post">
        <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
        <input type="hidden" name="exam_id" value="<?= $exam_id ?>">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Student Name</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $existingMarks = [];
                    $marksQuery = $conn->query("SELECT student_id, marks FROM grades WHERE exam_id = '$exam_id' AND subject_id = '$subject_id'");
                    while ($row = $marksQuery->fetch_assoc()) {
                        $existingMarks[$row['student_id']] = $row['marks'];
                    }
                    ?>
                    <?php while ($student = $students->fetch_assoc()): ?>
                        <?php $sid = $student['student_id']; ?>
                        <tr>
                            <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
                            <td>
                                <input type="number" name="grades[<?= $sid ?>]" value="<?= htmlspecialchars($existingMarks[$sid] ?? '') ?>" step="0.01" max="3" class="form-control">
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-primary">Save Grades</button>
    </form>
    <?php elseif ($load && $subject_id): ?>
        <div class="alert alert-warning">No students found for this search.</div>
    <?php endif; ?>
</div>
</body>
</html>
