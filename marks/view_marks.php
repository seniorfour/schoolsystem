<?php
require_once('../db.php');

// Auto-detect class, term, year
$class_options = $conn->query("SELECT DISTINCT c.class_id, c.name 
    FROM student_marks sm 
    JOIN classes c ON sm.class_id = c.class_id 
    ORDER BY c.name");

$term_options = $conn->query("SELECT DISTINCT term FROM student_marks ORDER BY term");
$year_options = $conn->query("SELECT DISTINCT year FROM student_marks ORDER BY year DESC");

// Get all subjects (code-based)
$subjects = [];
$subject_codes = [];
$res = $conn->query("SELECT subject_id, code FROM subjects ORDER BY code");
while ($row = $res->fetch_assoc()) {
    $subjects[] = $row['code'];
    $subject_codes[$row['subject_id']] = $row['code'];
}

// Filter parameters
$class_id = $_GET['class_id'] ?? '';
$term = $_GET['term'] ?? '';
$year = $_GET['year'] ?? '';

$student_marks = [];

if ($class_id && $term && $year) {
    $sql = "
        SELECT sm.student_id, sm.subject_id, sm.marks_obtained, s.first_name, s.last_name
        FROM student_marks sm
        JOIN students s ON sm.student_id = s.student_id
        WHERE sm.class_id = ? AND sm.term = ? AND sm.year = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $class_id, $term, $year);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $sid = $row['student_id'];
        $subject_id = $row['subject_id'];
        $subject_code = $subject_codes[$subject_id] ?? null;
        $student_name = $row['first_name'] . ' ' . $row['last_name'];

        if (!isset($student_marks[$sid])) {
            $student_marks[$sid] = ['name' => $student_name];
        }

        if ($subject_code) {
            $student_marks[$sid][$subject_code] = $row['marks_obtained'];
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Marks Summary</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
    <style>
        table.full-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.full-table th,
        table.full-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: center;
        }
        table.full-table th {
            background-color: #f8f9fa;
            position: sticky;
            top: 0;
            z-index: 1;
        }
    </style>
</head>
<body class="container mt-4">
    <h2>Marks Summary (by Subject Code)</h2>

    <form method="GET" class="row mb-4">
        <div class="col-md-3">
            <label>Class</label>
            <select name="class_id" class="form-select" required>
                <option value="">Select Class</option>
                <?php while ($c = $class_options->fetch_assoc()): ?>
                    <option value="<?= $c['class_id'] ?>" <?= $class_id == $c['class_id'] ? 'selected' : '' ?>>
                        <?= $c['name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="col-md-3">
            <label>Term</label>
            <select name="term" class="form-select" required>
                <option value="">Select Term</option>
                <?php while ($t = $term_options->fetch_assoc()): ?>
                    <option value="<?= $t['term'] ?>" <?= $term == $t['term'] ? 'selected' : '' ?>>
                        <?= $t['term'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="col-md-2">
            <label>Year</label>
            <select name="year" class="form-select" required>
                <option value="">Select Year</option>
                <?php while ($y = $year_options->fetch_assoc()): ?>
                    <option value="<?= $y['year'] ?>" <?= $year == $y['year'] ? 'selected' : '' ?>>
                        <?= $y['year'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="col-md-2 mt-4">
            <button type="submit" class="btn btn-primary w-100">Load</button>
        </div>
    </form>

    <?php if ($class_id && $term && $year): ?>
        <?php if (!empty($student_marks)): ?>
            <div class="mb-3 text-end">
                <button onclick="exportTableToExcel('marksTable', 'Marks_<?= $term ?>_<?= $year ?>')" class="btn btn-success">Download Excel</button>
            </div>

            <div class="table-responsive">
                <table class="full-table table table-striped" id="marksTable">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <?php foreach ($subjects as $code): ?>
                                <th><?= htmlspecialchars($code) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($student_marks as $data): ?>
                            <tr>
                                <td><?= $data['name'] ?></td>
                                <?php foreach ($subjects as $code): ?>
									<td><?= isset($data[$code]) ? intval($data[$code]) : '-' ?></td>

                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">No marks found for selected filters.</div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-info">Please select class, term, and year to view results.</div>
    <?php endif; ?>

    <script>
        function exportTableToExcel(tableID, filename = '') {
            const dataType = 'application/vnd.ms-excel';
            const tableSelect = document.getElementById(tableID);
            const tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

            const downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);

            if (navigator.msSaveOrOpenBlob) {
                const blob = new Blob(['\ufeff', tableHTML], { type: dataType });
                navigator.msSaveOrOpenBlob(blob, filename + '.xls');
            } else {
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
                downloadLink.download = filename ? filename + '.xls' : 'excel_data.xls';
                downloadLink.click();
            }
        }
    </script>
</body>
</html>
