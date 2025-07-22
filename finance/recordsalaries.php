<?php
require_once('../db.php');
$staff = $conn->query("SELECT staff_id, first_name, last_name FROM staff ORDER BY first_name, last_name");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $staff_id = $_POST['staff_id'];
    $amount = $_POST['amount'];
    $pay_date = $_POST['pay_date'];
    $remarks = $_POST['remarks'];

    $stmt = $conn->prepare("INSERT INTO salaries (staff_id, amount, pay_date, remarks) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('idss', $staff_id, $amount, $pay_date, $remarks);
    $stmt->execute();
    $stmt->close();

    $success = "Salary recorded!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Record Salary Payment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Record Salary Payment</h2>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <form method="post" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Staff Member</label>
            <select name="staff_id" class="form-select" required>
                <option value="">-- Select Staff --</option>
                <?php while($s = $staff->fetch_assoc()): ?>
                <option value="<?= $s['staff_id'] ?>"><?= htmlspecialchars($s['first_name'].' '.$s['last_name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Pay Date</label>
            <input type="date" name="pay_date" class="form-control" required value="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-12">
            <label class="form-label">Remarks</label>
            <input type="text" name="remarks" class="form-control">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-success">Record Salary</button>
        </div>
    </form>
</body>
</html>