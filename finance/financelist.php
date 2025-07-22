<?php
require_once('../db.php');

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM fees WHERE fee_id = $id");
    header("Location: feelist.php");
    exit;
}

// Fetch fees with class name
$result = $conn->query("SELECT f.*, c.name AS class_name, c.year FROM fees f LEFT JOIN classes c ON f.class_id = c.class_id ORDER BY f.due_date DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Fee Structure List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Fee Structure</h2>
    <a href="addfee.php" class="btn btn-primary mb-3">Add Fee</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th><th>Class</th><th>Term</th><th>Amount</th><th>Due Date</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['class_name'].' ('.$row['year'].')') ?></td>
                <td><?= htmlspecialchars($row['term']) ?></td>
                <td><?= htmlspecialchars($row['amount']) ?></td>
                <td><?= htmlspecialchars($row['due_date']) ?></td>
                <td>
                    <a href="addfee.php?id=<?= $row['fee_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="feelist.php?delete=<?= $row['fee_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this fee?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>