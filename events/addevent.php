<?php
require_once('../db.php');

// Get staff for organizer dropdown
$staff = $conn->query("SELECT staff_id, first_name, last_name FROM staff ORDER BY first_name, last_name");

$edit = isset($_GET['id']);
if ($edit) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM events WHERE event_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $event = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $event_date = $_POST['event_date'] ?? '';
    $location = $_POST['location'] ?? '';
    $organizer_id = $_POST['organizer_id'] ?? null;
    $organizer_id = $organizer_id === '' ? null : $organizer_id;

    if ($edit) {
        $stmt = $conn->prepare("UPDATE events SET name=?, description=?, event_date=?, location=?, organizer_id=? WHERE event_id=?");
        $stmt->bind_param('ssssii', $name, $description, $event_date, $location, $organizer_id, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO events (name, description, event_date, location, organizer_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssi', $name, $description, $event_date, $location, $organizer_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: eventlist.php");
    exit;
}

$values = [
    'name'         => $edit ? $event['name'] : '',
    'description'  => $edit ? $event['description'] : '',
    'event_date'   => $edit ? $event['event_date'] : '',
    'location'     => $edit ? $event['location'] : '',
    'organizer_id' => $edit ? $event['organizer_id'] : ''
];
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $edit ? "Edit Event" : "Add Event" ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2><?= $edit ? "Edit Event" : "Add New Event" ?></h2>
    <a href="eventlist.php" class="btn btn-secondary mb-3">&larr; Back to Event List</a>
    <form method="post" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Event Name</label>
            <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($values['name']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Event Date</label>
            <input type="date" name="event_date" class="form-control" required value="<?= htmlspecialchars($values['event_date']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($values['location']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Organizer</label>
            <select name="organizer_id" class="form-select">
                <option value="">-- None --</option>
                <?php
                if ($staff->num_rows > 0) {
                    $staff->data_seek(0);
                    while($s = $staff->fetch_assoc()):
                ?>
                <option value="<?= $s['staff_id'] ?>" <?= $s['staff_id']==$values['organizer_id']?'selected':'' ?>>
                    <?= htmlspecialchars($s['first_name'].' '.$s['last_name']) ?>
                </option>
                <?php endwhile; } ?>
            </select>
        </div>
        <div class="col-12">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($values['description']) ?></textarea>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-success"><?= $edit ? "Update" : "Add" ?> Event</button>
        </div>
    </form>
</body>
</html>