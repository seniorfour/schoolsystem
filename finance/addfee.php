<?php
require_once('../db.php');
$classes = $conn->query("SELECT class_id, name, year FROM classes ORDER BY year, name");

$edit = isset($_GET['id']);
if ($edit) {
    $id = intval($_GET['id']);
    $fee = $conn->query("SELECT * FROM fees WHERE fee_id = $id")->fetch_assoc();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $class_id = $_POST['class_id'];
    $term = $_POST['term'];
    $due_date = $_POST['due_date'];

    if ($edit) {
        $stmt = $conn->prepare("UPDATE fees SET name=?, amount=?, class_id=?, term=?, due_date=? WHERE fee_id=?");
        $stmt->bind_param('sdissi', $name, $amount, $class_id, $term, $due_date, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO fees (name, amount, class_id, term, due_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sdiss', $name, $amount, $class_id, $term, $due_date);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: feelist.php");
    exit;
}
$values = [
    'name' => $edit ? $fee['name'] : '',
    'amount' => $edit ? $fee['amount'] : '',
    'class_id' => $edit ? $fee['class_id'] : '',
    'term' => $edit ? $fee['term'] : '',
    'due_date' => $edit ? $fee['due_date'] : ''
];
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $edit ? "Edit Fee" : "Add Fee" ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2><?= $edit ? "Edit Fee" : "Add Fee" ?></h2>
    <a href="feelist.php" class="btn btn-secondary mb-3">&larr; Back to Fee List</a>
    <form method="post" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Fee Name</label>
            <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($values['name']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" required value="<?= htmlspecialchars($values['amount']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Class</label>
            <select name="class_id" class="form-select" required>
                <option value="">-- Select Class --</option>
                <?php $classes->data_seek(0); while($c = $classes->fetch_assoc()): ?>
                <option value="<?= $c['class_id'] ?>" <?= $c['class_id']==$values['class_id']?'selected':'' ?>>
                    <?= htmlspecialchars($c['name'].' ('.$c['year'].')') ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Term</label>
            <input type="text" name="term" class="form-control" required value="<?= htmlspecialchars($values['term']) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control" required value="<?= htmlspecialchars($values['due_date']) ?>">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-success"><?= $edit ? "Update" : "Add" ?> Fee</button>
        </div>
    </form>
</body>
</html>