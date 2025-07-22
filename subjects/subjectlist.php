<?php
require_once('../db.php');

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM subjects WHERE subject_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header("Location: subjectlist.php");
    exit;
}

// Fetch Subject List
$result = $conn->query("SELECT * FROM subjects ORDER BY subject_id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subject List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
        }
        .card-style {
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body>

<?php include '../header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="container-fluid">
        <h3 class="mb-3">Subject List</h3>
        <a href="addsubject.php" class="btn btn-primary mb-3">+ Add New Subject</a>

        <div class="card-style">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['subject_id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['code']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td>
                            <a href="addsubject.php?id=<?= $row['subject_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="subjectlist.php?delete=<?= $row['subject_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this subject?');">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
</body>
</html>
