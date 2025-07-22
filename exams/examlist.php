<?php
require_once('../db.php');

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM exams WHERE exam_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header("Location: examlist.php");
    exit;
}

// Fetch exams (without class join now)
$query = "SELECT * FROM exams ORDER BY date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exam List</title>
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
        table th, table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<?php include '../header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="container-fluid">
        <h3 class="mb-3">Exam List</h3>
        <a href="addexam.php" class="btn btn-primary mb-3">Add New Exam</a>

        <div class="card-style">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Exam Name</th>
                        <th>Year</th>
                        <th>Term</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['exam_id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td><?= htmlspecialchars($row['term']) ?></td>
                        <td>
                            <a href="addexam.php?id=<?= $row['exam_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="examlist.php?delete=<?= $row['exam_id'] ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Delete this exam?');">Delete</a>
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
