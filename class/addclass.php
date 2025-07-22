<?php
require_once('../db.php');

// Get staff for teacher dropdown
$teachers = $conn->query("SELECT staff_id, first_name, last_name FROM staff");

$edit = isset($_GET['id']);
if ($edit) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM classes WHERE class_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $class = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $year = $_POST['year'] ?? '';
    $teacher_id = $_POST['teacher_id'] ?? null;
    $teacher_id = $teacher_id === '' ? null : $teacher_id;

    if ($edit) {
        $stmt = $conn->prepare("UPDATE classes SET name=?, year=?, teacher_id=? WHERE class_id=?");
        $stmt->bind_param('siii', $name, $year, $teacher_id, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO classes (name, year, teacher_id) VALUES (?, ?, ?)");
        $stmt->bind_param('sii', $name, $year, $teacher_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: classlist.php");
    exit;
}

$values = [
    'name' => $edit ? $class['name'] : '',
    'year' => $edit ? $class['year'] : '',
    'teacher_id' => $edit ? $class['teacher_id'] : ''
];
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $edit ? "Edit Class" : "Add Class" ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            margin: 0;
        }
        .main-content {
            margin-left: auto; /* adjust according to your sidebar */
            padding: 20px;
        }
    </style>
</head>
<body>
    <?php include '../header.php'; ?>
    <?php include '../includes/sidebar.php'; ?>

    <div class="main-content">
        <div class="container py-4">
            <h2><?= $edit ? "Edit Class" : "Add New Class" ?></h2>
            <a href="classlist.php" class="btn btn-secondary mb-3">&larr; Back to Class List</a>

            <form method="post" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Class Name</label>
                    <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($values['name']) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Year</label>
                    <input type="number" name="year" class="form-control" required value="<?= htmlspecialchars($values['year']) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Class Teacher</label>
                    <select name="teacher_id" class="form-select">
                        <option value="">-- None --</option>
                        <?php
                        if ($teachers->num_rows > 0) {
                            $teachers->data_seek(0);
                            while ($t = $teachers->fetch_assoc()): ?>
                                <option value="<?= $t['staff_id'] ?>" <?= $t['staff_id'] == $values['teacher_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($t['first_name'] . ' ' . $t['last_name']) ?>
                                </option>
                        <?php endwhile; } ?>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success"><?= $edit ? "Update" : "Add" ?> Class</button>
                </div>
            </form>
        </div>
    </div>

    <?php include '../footer.php'; ?>
</body>
</html>
