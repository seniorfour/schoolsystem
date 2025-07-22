<?php
require_once('../db.php');

// Fetch students
$students = $conn->query("SELECT student_id, first_name, last_name FROM students ORDER BY first_name");

$marks = [];
$selected_student = null;

if (!empty($_GET['student_id'])) {
    $id = intval($_GET['student_id']);
    $selected_student = $conn->query("SELECT * FROM students WHERE student_id = $id")->fetch_assoc();

    $marks = $conn->query("
        SELECT sm.*, s.name AS subject_name, c.name AS class_name
        FROM student_marks sm
        JOIN subjects s ON sm.subject_id = s.subject_id
        JOIN classes c ON sm.class_id = c.class_id
        WHERE sm.student_id = $id
        ORDER BY sm.year DESC, sm.term
    ");
}

function getGrade($percent) {
    if ($percent >= 80) return ['A', 'Excellent'];
    if ($percent >= 70) return ['B', 'Very Good'];
    if ($percent >= 60) return ['C', 'Good'];
    if ($percent >= 50) return ['D', 'Fair'];
    if ($percent >= 40) return ['E', 'Poor'];
    return ['F', 'Fail'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Report Card</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            background: white;
        }

        /* A4 page layout */
        .a4-page {
            width: 210mm;
            height: 297mm;
            padding: 20mm;
            box-sizing: border-box;
            margin: auto;
        }

        @media print {
            body, html {
                width: 210mm;
                height: 297mm;
            }
            .a4-page {
                margin: 0;
                box-shadow: none;
                border: none;
            }
        }

        .report-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            font-family: 'Times New Roman', Times, serif;
            margin-bottom: 30px;
        }

        .info-table td {
            padding: 6px 12px;
        }

        .table-bordered th, .table-bordered td {
            text-align: center;
            vertical-align: middle;
        }

        .form-section {
            margin-bottom: 20px;
        }

        select, button {
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="a4-page">
    <div class="report-title">Springfield High School Matugga</div>

    <form method="GET" class="form-section">
        <label><strong>Select Student:</strong></label>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <select name="student_id" class="form-select" style="max-width: 300px;" required>
                <option value="">-- Choose Student --</option>
                <?php while($s = $students->fetch_assoc()): ?>
                    <option value="<?= $s['student_id'] ?>" <?= (isset($_GET['student_id']) && $_GET['student_id'] == $s['student_id']) ? 'selected' : '' ?>>
                        <?= $s['first_name'] . ' ' . $s['last_name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit" class="btn btn-primary">View Report</button>
        </div>
    </form>

    <?php if ($selected_student): ?>
        <?php $marks->data_seek(0); $first_mark = $marks->fetch_assoc(); ?>
        <table class="table table-sm info-table mb-4">
            <tr>
                <td><strong>Name:</strong> <?= $selected_student['first_name'] . ' ' . $selected_student['last_name'] ?></td>
                <td><strong>Class:</strong> <?= $first_mark['class_name'] ?? '' ?></td>
                <td><strong>Gender:</strong> <?= $selected_student['gender'] ?? '' ?></td>
            </tr>
            <tr>
                <td><strong>House:</strong> <?= $selected_student['house'] ?? 'N/A' ?></td>
                <td><strong>Term:</strong> <?= $first_mark['term'] ?? '' ?></td>
                <td><strong>Year:</strong> <?= $first_mark['year'] ?? '' ?></td>
            </tr>
        </table>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Subject</th>
                    <th>Score</th>
                    <th>Grade</th>
                    <th>Descriptor</th>
                    <th>Initial</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $marks->data_seek(0);
                while($m = $marks->fetch_assoc()):
                    $percentage = ($m['marks_obtained'] / $m['max_marks']) * 100;
                    [$grade, $desc] = getGrade($percentage);
                ?>
                    <tr>
                        <td><?= $m['subject_name'] ?></td>
                        <td><?= $m['marks_obtained'] ?>/<?= $m['max_marks'] ?></td>
                        <td><?= $grade ?></td>
                        <td><?= $desc ?></td>
                        <td></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
