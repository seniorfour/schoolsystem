<?php
require_once('../db.php');
$classes = $conn->query("SELECT class_id, name, year FROM classes ORDER BY year, name");
$class_id = $_GET['class_id'] ?? '';
$data = [];

if ($class_id) {
    $fees = $conn->query("SELECT * FROM fees WHERE class_id = $class_id");
    while ($fee = $fees->fetch_assoc()) {
        $fid = $fee['fee_id'];
        $collected = $conn->query("SELECT SUM(amount_paid) as c FROM payments WHERE fee_id = $fid")->fetch_assoc()['c'] ?? 0;
        $data[] = [
            'name' => $fee['name'],
            'term' => $fee['term'],
            'amount' => $fee['amount'],
            'due_date' => $fee['due_date'],
            'collected' => $collected,
        ];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Fee Summary</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Fee Summary</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">&larr; Back to Dashboard</a>
    <form method="get" class="row g-3 mb-4">
        <div class="col-md-6">
            <label class="form-label">Class</label>
            <select name="class_id" class="form-select" required>
                <option value="">-- Select Class --</option>
                <?php while($c = $classes->fetch_assoc()): ?>
                <option value="<?= $c['class_id'] ?>" <?= $c['class_id']==$class_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['name'].' ('.$c['year'].')') ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-success">Show</button>
        </div>
    </form>
    <?php if ($class_id): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Fee Name</th><th>Term</th><th>Amount</th><th>Due Date</th><th>Collected</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['term']) ?></td>
                <td><?= htmlspecialchars($row['amount']) ?></td>
                <td><?= htmlspecialchars($row['due_date']) ?></td>
                <td><?= htmlspecialchars($row['collected']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</body>
</html>