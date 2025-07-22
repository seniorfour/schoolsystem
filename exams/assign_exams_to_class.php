<?php
require_once('../db.php');

$success = $error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $exam_id = intval($_POST['exam_id']);
    $class_ids = $_POST['class_ids'] ?? [];

    if ($exam_id && !empty($class_ids)) {
        foreach ($class_ids as $class_id) {
            $class_id = intval($class_id);
            // Avoid duplicates
            $check = $conn->prepare("SELECT 1 FROM exam_class WHERE exam_id = ? AND class_id = ?");
            $check->bind_param('ii', $exam_id, $class_id);
            $check->execute();
            $check->store_result();

            if ($check->num_rows == 0) {
                $stmt = $conn->prepare("INSERT INTO exam_class (exam_id, class_id) VALUES (?, ?)");
                $stmt->bind_param('ii', $exam_id, $class_id);
                $stmt->execute();
                $stmt->close();
            }
            $check->close();
        }
        $success = "Exam assigned successfully.";
    } else {
        $error = "Please select an exam and at least one class.";
    }
}

// Fetch data
$exams = $conn->query("SELECT exam_id, name FROM exams ORDER BY name");
$classes = $conn->query("SELECT class_id, name, year FROM classes ORDER BY year DESC, name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Exam to Classes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
        }
        .card-style {
            background: #fff;
            padding: 20px;
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
        <h3 class="mb-4">Assign Exam to Classes</h3>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <div class="card-style">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="exam_id" class="form-label">Select Exam</label>
                    <select name="exam_id" id="exam_id" class="form-select" required>
                        <option value="">-- Choose Exam --</option>
                        <?php while ($exam = $exams->fetch_assoc()): ?>
                            <option value="<?= $exam['exam_id'] ?>"><?= htmlspecialchars($exam['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Select Classes</label>
                    <div class="form-check">
                        <?php while ($class = $classes->fetch_assoc()): ?>
                            <div>
                                <input type="checkbox" name="class_ids[]" value="<?= $class['class_id'] ?>" class="form-check-input" id="class_<?= $class['class_id'] ?>">
                                <label class="form-check-label" for="class_<?= $class['class_id'] ?>">
                                    <?= htmlspecialchars($class['name'] . ' (' . $class['year'] . ')') ?>
                                </label>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Assign Exam</button>
            </form>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
</body>
</html>
