<?php
require_once('../db.php');

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM classes WHERE class_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header("Location: classlist.php");
    exit;
}

// Fetch Class List (with teacher name)
$query = "SELECT c.*, s.first_name, s.last_name FROM classes c 
          LEFT JOIN staff s ON c.teacher_id = s.staff_id 
          ORDER BY c.class_id ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Class List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .main-content {
            margin-left: auto;
            padding: 20px;
        }
    </style>
</head>
<body>
    <?php include '../header.php'; ?>
    <?php include '../includes/sidebar.php'; ?>

    <div class="main-content">
        <div class="container py-4">
            <h2>Class List</h2>
            <a href="addclass.php" class="btn btn-primary mb-3">Add New Class</a>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Year</th>
                        <th>Teacher</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['class_id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['year']) ?></td>
                        <td><?= htmlspecialchars(trim($row['first_name'].' '.$row['last_name'])) ?></td>
                        <td>
                            <a href="addclass.php?id=<?= $row['class_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="classlist.php?delete=<?= $row['class_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this class?');">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include '../footer.php'; ?>
</body>
</html>
