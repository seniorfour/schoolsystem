<?php
require_once('../db.php');
$students = $conn->query("SELECT student_id, first_name, last_name FROM students ORDER BY first_name, last_name");
$student_id = $_GET['student_id'] ?? '';

$data = [];
if ($student_id) {
    // Get all fees by class (assuming a student has one class per year, can be adjusted)
    $fees = $conn->query("SELECT f.*, c.name AS class_name, c.year FROM fees f
        JOIN enrollments e ON f.class_id = e.class_id
        JOIN classes c ON f.class_id = c.class_id
        WHERE e.student_id = $student_id");
    while ($fee = $fees->fetch_assoc()) {
        // Get total paid for this fee
        $fee_id = $fee['fee_id'];
        $paid = $conn->query("SELECT SUM(amount_paid) as total_paid FROM payments WHERE student_id = $student_id AND fee_id = $fee_id")->fetch_assoc()['total_paid'] ?? 0;
        $data[] = [
            'name' => $fee['name'],
            'class' => $fee['class_name'].' ('.$fee['year'].')',
            'term' => $fee['term'],
            'amount' => $fee['amount'],
            'due_date' => $fee['due_date'],
            'paid' => $paid,
            'status' => ($paid >= $fee['amount']) ? 'Paid' : 'Due'
        ];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Payment Status</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Student Payment Status</h2>
    <form method="get" class="row g-3 mb-4">
        <div class="col-md-6">
            <label class="form-label">Student</label>
            <select name="student_id" class="form-select" required>
                <option value="">-- Select Student --</option>
                <?php while($s = $students->fetch_assoc()): ?>
                <option value="<?= $s['student_id'] ?>" <?= $s['student_id']==$student_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['first_name'].' '.$s['last_name']) ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-success">View Status</button>
        </div>
    </form>
    <?php if ($student_id): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Fee Name</th><th>Class</th><th>Term</th><th>Amount</th><th>Paid</th><th>Status</th><th>Due Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['class']) ?></td>
                    <td><?= htmlspecialchars($row['term']) ?></td>
                    <td><?= htmlspecialchars($row['amount']) ?></td>
                    <td><?= htmlspecialchars($row['paid']) ?></td>
                    <td><?= $row['status'] == 'Paid' ?
                        '<span class="badge bg-success">Paid</span>' :
                        '<span class="badge bg-warning text-dark">Due</span>' ?></td>
                    <td><?= htmlspecialchars($row['due_date']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>