<?php
require_once('../db.php');

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM events WHERE event_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header("Location: eventlist.php");
    exit;
}

// Fetch Events (with organizer name)
$query = "SELECT e.*, s.first_name, s.last_name
          FROM events e
          LEFT JOIN staff s ON e.organizer_id = s.staff_id
          ORDER BY e.event_date DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Event List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Event List</h2>
    <a href="addevent.php" class="btn btn-primary mb-3">Add New Event</a>
    <a href="calendar.php" class="btn btn-info mb-3">Calendar View</a>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th><th>Name</th><th>Date</th><th>Location</th><th>Organizer</th><th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['event_id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['event_date']) ?></td>
            <td><?= htmlspecialchars($row['location']) ?></td>
            <td><?= htmlspecialchars(trim($row['first_name'].' '.$row['last_name'])) ?></td>
            <td>
                <a href="addevent.php?id=<?= $row['event_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="eventlist.php?delete=<?= $row['event_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this event?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>