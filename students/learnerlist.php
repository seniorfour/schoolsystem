<?php
require_once('../db.php');

// Handle Delete (via GET)
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header("Location: learnerlist.php");
    exit;
}

// Fetch Learner List (with guardian details)
$query = "SELECT s.*, 
                 g.first_name AS gfname, 
                 g.last_name AS glname, 
                 g.phone AS gphone, 
                 g.email AS gemail, 
                 g.address AS gaddress, 
                 g.relationship AS grelationship
          FROM students s
          LEFT JOIN guardians g ON s.guardian_id = g.guardian_id
          ORDER BY s.student_id ASC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Learner List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Learner List</h2>
    <a href="addlearner.php" class="btn btn-primary mb-3">Add New Learner</a>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th><th>Name</th><th>Gender</th><th>DOB</th>
            <th>Email</th><th>Phone</th>
            <th>Guardian Name</th>
            <th>Guardian Phone</th>
            <th>Guardian Email</th>
            <th>Guardian Relationship</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['student_id'] ?></td>
            <td><?= htmlspecialchars($row['first_name'].' '.$row['last_name']) ?></td>
            <td><?= htmlspecialchars($row['gender']) ?></td>
            <td><?= htmlspecialchars($row['dob']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars(trim($row['gfname'].' '.$row['glname'])) ?></td>
            <td><?= htmlspecialchars($row['gphone']) ?></td>
            <td><?= htmlspecialchars($row['gemail']) ?></td>
            <td><?= htmlspecialchars($row['grelationship']) ?></td>
            <td>
                <a href="addlearner.php?id=<?= $row['student_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="learnerlist.php?delete=<?= $row['student_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this learner?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>