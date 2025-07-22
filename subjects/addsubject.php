<?php
require_once('../db.php');

$edit = isset($_GET['id']);
if ($edit) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM subjects WHERE subject_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $subject = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $code = $_POST['code'] ?? '';
    $description = $_POST['description'] ?? '';

    if ($edit) {
        $stmt = $conn->prepare("UPDATE subjects SET name=?, code=?, description=? WHERE subject_id=?");
        $stmt->bind_param('sssi', $name, $code, $description, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO subjects (name, code, description) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $name, $code, $description);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: subjectlist.php");
    exit;
}

$values = [
    'name' => $edit ? $subject['name'] : '',
    'code' => $edit ? $subject['code'] : '',
    'description' => $edit ? $subject['description'] : ''
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $edit ? "Edit Subject" : "Add Subject" ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
        }
        .form-container {
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body>

<?php include '../header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="container-fluid">
        <h3 class="mb-3"><?= $edit ? "Edit Subject" : "Add New Subject" ?></h3>
        <a href="subjectlist.php" class="btn btn-secondary mb-3">&larr; Back to Subject List</a>

        <div class="form-container">
            <form method="post" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Subject Name</label>
                    <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($values['name']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Subject Code</label>
                    <input type="text" name="code" class="form-control" required value="<?= htmlspecialchars($values['code']) ?>">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($values['description']) ?></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success"><?= $edit ? "Update" : "Add" ?> Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
</body>
</html>
