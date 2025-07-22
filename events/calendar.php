<?php
require_once('../db.php');

// Fetch all events
$query = "SELECT e.*, s.first_name, s.last_name
          FROM events e
          LEFT JOIN staff s ON e.organizer_id = s.staff_id
          ORDER BY e.event_date ASC";
$result = $conn->query($query);

// Group events by date
$calendar = [];
while($row = $result->fetch_assoc()) {
    $calendar[$row['event_date']][] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Event Calendar View</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .calendar-day { margin-bottom:2em; }
        .event-box { border-radius:5px; padding:10px; margin-bottom:10px; background:#f8f9fa; }
    </style>
</head>
<body class="container py-4">
    <h2>Event Calendar View</h2>
    <a href="eventlist.php" class="btn btn-secondary mb-3">&larr; Back to Event List</a>
    <?php if (empty($calendar)): ?>
        <div class="alert alert-warning">No events found.</div>
    <?php else: ?>
        <?php foreach ($calendar as $date => $events): ?>
            <div class="calendar-day">
                <h5 class="text-primary"><?= htmlspecialchars($date) ?></h5>
                <?php foreach ($events as $event): ?>
                    <div class="event-box">
                        <strong><?= htmlspecialchars($event['name']) ?></strong> 
                        <span class="badge bg-info text-dark"><?= htmlspecialchars($event['location']) ?></span>
                        <br>
                        <em>Organized by <?= htmlspecialchars(trim($event['first_name'].' '.$event['last_name'])) ?></em>
                        <p><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>