<?php
require_once('../db.php');
$edit = isset($_GET['id']);
if ($edit) {
    $id = intval($_GET['id']);
    $transaction = $conn->query("SELECT * FROM transactions WHERE transaction_id = $id")->fetch_assoc();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $amount = $_POST['amount'];
    $type = $_POST['type'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    if ($edit) {
        $stmt = $conn->prepare("UPDATE transactions SET date=?, amount=?, type=?, category=?, description=? WHERE transaction_id=?");
        $stmt->bind_param('sdsssi', $date, $amount, $type, $category, $description, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO transactions (date, amount, type, category, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sdsss', $date, $amount, $type, $category, $description);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: transactionlist.php");
    exit;
}
$values = [
    'date' => $edit ? $transaction['date'] : date('Y-m-d'),
    'amount' => $edit ? $transaction['amount'] : '',
    'type' => $edit ? $transaction['type'] : '',
    'category' => $edit ? $transaction['category'] : '',
    'description' => $edit ? $transaction['description'] : ''
];
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $edit ? "Edit Transaction" : "Add Transaction" ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2><?= $edit ? "Edit Transaction" : "Add Transaction" ?></h2>
    <a href="transactionlist.php" class="btn btn-secondary mb-3">&larr; Back to Transactions</a>
    <form method="post" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" required value="<?= htmlspecialchars($values['date']) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" required value="<?= htmlspecialchars($values['amount']) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Type</label>
            <select name="type" class="form-select" required>
                <option value="">-- Select --</option>
                <option value="income" <?= $values['type']=='income'?'selected':'' ?>>Income</option>
                <option value="expense" <?= $values['type']=='expense'?'selected':'' ?>>Expense</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($values['category']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Description</label>
            <input type="text" name="description" class="form-control" value="<?= htmlspecialchars($values['description']) ?>">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-success"><?= $edit ? "Update" : "Add" ?> Transaction</button>
        </div>
    </form>
</body>
</html>