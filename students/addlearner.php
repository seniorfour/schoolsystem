<?php
require_once('../db.php');

// Fetch classes
$classes = $conn->query("SELECT class_id, name, year FROM classes ORDER BY year DESC, name ASC");

$success = $error = '';
$edit = isset($_GET['id']);
$values = [
    'first_name' => '', 'last_name' => '', 'dob' => '', 'gender' => '',
    'address' => '', 'email' => '', 'phone' => '',
    'class_id' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $dob        = $_POST['dob'] ?? '';
    $gender     = $_POST['gender'] ?? '';
    $address    = trim($_POST['address'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $class_id   = $_POST['class_id'] ?? null;

    // New Guardian
    $parent_name = trim($_POST['parent_name'] ?? '');
    $g_phone     = trim($_POST['g_phone'] ?? '');
    $g_email     = trim($_POST['g_email'] ?? '');
    $g_address   = trim($_POST['g_address'] ?? '');
    $g_relation  = trim($_POST['g_relationship'] ?? '');

    $guardian_id = null;

    // Insert new guardian
    $stmt = $conn->prepare("INSERT INTO guardians (parent_name, phone, email, address, relationship) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sssss', $parent_name, $g_phone, $g_email, $g_address, $g_relation);
    if ($stmt->execute()) {
        $guardian_id = $conn->insert_id;
    } else {
        $error = "Error creating guardian: " . $stmt->error;
    }
    $stmt->close();

    if ($guardian_id) {
        $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, dob, gender, address, email, phone, guardian_id, class_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssssii', $first_name, $last_name, $dob, $gender, $address, $email, $phone, $guardian_id, $class_id);
        if ($stmt->execute()) {
            $new_student_id = $conn->insert_id;

            // Enroll student
            $year = date('Y');
            $enroll_stmt = $conn->prepare("INSERT INTO enrollments (student_id, class_id, academic_year) VALUES (?, ?, ?)");
            $enroll_stmt->bind_param('iis', $new_student_id, $class_id, $year);
            $enroll_stmt->execute();
            $enroll_stmt->close();

            header("Location: ../dashboard.php");
            exit();
        } else {
            $error = "Error saving student: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Learner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light p-4">
<div class="container">
    <h2 class="mb-4">Register New Learner</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Last Name</label>
            <input type="text" name="last_name" class="form-control" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Date of Birth</label>
            <input type="date" name="dob" class="form-control" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select" required>
                <option value="">-- Select Gender --</option>
                <option>Male</option>
                <option>Female</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Class</label>
            <select name="class_id" class="form-select" required>
                <option value="">-- Select Class --</option>
                <?php while($cls = $classes->fetch_assoc()): ?>
                    <option value="<?= $cls['class_id'] ?>"><?= $cls['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control">
        </div>

        <div class="col-md-4">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="col-md-4">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control">
        </div>

        <hr class="mt-4 mb-3">

        <h5 class="mt-2">Guardian Information</h5>

        <div class="col-md-4">
            <label class="form-label">Parent Name</label>
            <input type="text" name="parent_name" class="form-control" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Phone</label>
            <input type="text" name="g_phone" class="form-control" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Email</label>
            <input type="email" name="g_email" class="form-control">
        </div>

        <div class="col-md-6">
            <label class="form-label">Address</label>
            <textarea name="g_address" class="form-control" required></textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label">Relationship</label>
            <select name="g_relationship" class="form-select" required>
                <option value="">-- Select Relationship --</option>
                <option value="Parent">Parent</option>
                <option value="Guardian">Guardian</option>
            </select>
        </div>

        <div class="col-12 mt-4">
            <button type="submit" class="btn btn-primary">Register</button>
        </div>
    </form>
</div>
</body>
</html>
