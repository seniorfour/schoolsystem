<?php
require_once('../db.php');

$edit = isset($_GET['id']);
if ($edit) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM exams WHERE exam_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $exam = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $date = $_POST['date'] ?? '';
    $term = $_POST['term'] ?? '';

    if ($edit) {
        $stmt = $conn->prepare("UPDATE exams SET name=?, date=?, term=? WHERE exam_id=?");
        $stmt->bind_param('sssi', $name, $date, $term, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO exams (name, date, term) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $name, $date, $term);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: examlist.php");
    exit;
}

$values = [
    'name' => $edit ? $exam['name'] : '',
    'date' => $edit ? $exam['date'] : date('Y'), // default to current year
    'term' => $edit ? $exam['term'] : ''
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $edit ? "Edit Exam" : "Add Exam" ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
        }
        .card-style {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<?php include '../header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="container-fluid">
        <h3 class="mb-3"><?= $edit ? "Edit Exam" : "Add New Exam" ?></h3>
        <a href="examlist.php" class="btn btn-secondary mb-3">&larr; Back to Exam List</a>

        <div class="card-style">
            <form method="post" class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Exam Name</label>
                    <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($values['name']) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Year</label>
                    <select name="date" class="form-select" required>
                        <option value="">-- Select Year --</option>
                        <?php
                        $currentYear = date('Y');
                        for ($y = $currentYear; $y >= 2000; $y--):
                        ?>
                            <option value="<?= $y ?>" <?= $values['date'] == $y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Term</label>
                    <select name="term" class="form-select" required>
                        <option value="">-- Select Term --</option>
                        <option value="Term I" <?= $values['term'] == 'Term I' ? 'selected' : '' ?>>Term I</option>
                        <option value="Term II" <?= $values['term'] == 'Term II' ? 'selected' : '' ?>>Term II</option>
                        <option value="Term III" <?= $values['term'] == 'Term III' ? 'selected' : '' ?>>Term III</option>
                    </select>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-success"><?= $edit ? "Update" : "Add" ?> Exam</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
</body>
</html>
