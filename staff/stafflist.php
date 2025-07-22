<?php
require_once('../db.php');

// Handle Delete (optional, via GET)
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM staff WHERE staff_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header("Location: stafflist.php");
    exit;
}

// Fetch Staff List
$result = $conn->query("SELECT * FROM staff ORDER BY staff_id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
        }
        .table-container {
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<?php include '../header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Staff List</h3>
            <a href="addstaff.php" class="btn btn-primary">+ Add New Staff</a>
        </div>

        <div class="table-container">
            <table class="table table-striped table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Role</th>
                        <th scope="col">Department</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['staff_id'] ?></td>
                        <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                        <td><?= htmlspecialchars($row['department']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td>
                            <a href="addstaff.php?id=<?= $row['staff_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="stafflist.php?delete=<?= $row['staff_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this staff?');">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if ($result->num_rows === 0): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">No staff found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
</body>
</html>
