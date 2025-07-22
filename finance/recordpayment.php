<?php
require_once('../db.php');

$students = $conn->query("SELECT student_id, first_name, last_name FROM students ORDER BY first_name, last_name");
$fees = $conn->query("SELECT fee_id, name, amount FROM fees ORDER BY name");
$methods = ['cash','card','transfer','other'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $fee_id = $_POST['fee_id'];
    $amount_paid = $_POST['amount_paid'];
    $payment_date = $_POST['payment_date'];
    $method = $_POST['method'];

    $stmt = $conn->prepare("INSERT INTO payments (student_id, fee_id, amount_paid, payment_date, method) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('iidss', $student_id, $fee_id, $amount_paid, $payment_date, $method);
    $stmt->execute();
    $stmt->close();

    $success = "Payment recorded!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Record Payment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Record Payment</h2>
    <a href="paymentlist.php" class="btn btn-secondary mb-3">&larr; Payment List</a>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <form method="post" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Student</label>
            <select name="student_id" class="form-select" required>
                <option value="">-- Select Student --</option>
                <?php while($s = $students->fetch_assoc()): ?>
                <option value="<?= $s['student_id'] ?>"><?= htmlspecialchars($s['first_name'].' '.$s['last_name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Fee</label>
            <select name="fee_id" class="form-select" required>
                <option value="">-- Select Fee --</option>
                <?php while($f = $fees->fetch_assoc()): ?>
                <option value="<?= $f['fee_id'] ?>"><?= htmlspecialchars($f['name'].' ('.$f['amount'].')') ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Amount Paid</label>
            <input type="number" step="0.01" name="amount_paid" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Payment Date</label>
            <input type="date" name="payment_date" class="form-control" required value="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Method</label>
            <select name="method" class="form-select" required>
                <option value="">-- Select --</option>
                <?php foreach ($methods as $m): ?>
                <option value="<?= $m ?>"><?= ucfirst($m) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-success">Record Payment</button>
        </div>
    </form>
</body>
</html>