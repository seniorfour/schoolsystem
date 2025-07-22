<?php
require_once('../db.php');

// If editing, get staff data
$edit = isset($_GET['id']);
if ($edit) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM staff WHERE staff_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $staff = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $role = $_POST['role'] ?? '';
    $department = $_POST['department'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($edit) {
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE staff SET first_name=?, last_name=?, dob=?, role=?, department=?, email=?, phone=?, address=?, password=? WHERE staff_id=?");
            $stmt->bind_param('sssssssssi', $first_name, $last_name, $dob, $role, $department, $email, $phone, $address, $password_hash, $id);
        } else {
            $stmt = $conn->prepare("UPDATE staff SET first_name=?, last_name=?, dob=?, role=?, department=?, email=?, phone=?, address=? WHERE staff_id=?");
            $stmt->bind_param('ssssssssi', $first_name, $last_name, $dob, $role, $department, $email, $phone, $address, $id);
        }
        $stmt->execute();
        $stmt->close();
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO staff (first_name, last_name, dob, role, department, email, phone, address, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssssss', $first_name, $last_name, $dob, $role, $department, $email, $phone, $address, $password_hash);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: stafflist.php");
    exit;
}

$values = [
    'first_name' => $edit ? $staff['first_name'] : '',
    'last_name'  => $edit ? $staff['last_name']  : '',
    'dob'        => $edit ? $staff['dob']        : '',
    'role'       => $edit ? $staff['role']       : '',
    'department' => $edit ? $staff['department'] : '',
    'email'      => $edit ? $staff['email']      : '',
    'phone'      => $edit ? $staff['phone']      : '',
    'address'    => $edit ? $staff['address']    : ''
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $edit ? "Edit Staff" : "Add Staff" ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .content-wrapper {
            margin-left: 250px; /* Adjust if sidebar width differs */
            padding: 20px;
        }
        .form-container {
            background-color: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<?php include '../header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="container-fluid">
        <h3 class="mb-4"><?= $edit ? "Edit Staff" : "Add New Staff" ?></h3>
        <a href="stafflist.php" class="btn btn-outline-secondary mb-3">&larr; Back to Staff List</a>

        <div class="form-container">
            <form method="post" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">First Name</label>
                    <input type="text" name="first_name" class="form-control" required value="<?= htmlspecialchars($values['first_name']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" class="form-control" required value="<?= htmlspecialchars($values['last_name']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="dob" class="form-control" value="<?= htmlspecialchars($values['dob']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Role</label>
                    <input type="text" name="role" class="form-control" value="<?= htmlspecialchars($values['role']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Department</label>
                    <input type="text" name="department" class="form-control" value="<?= htmlspecialchars($values['department']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($values['email']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($values['phone']) ?>">
                </div>
                <div class="col-12">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control"><?= htmlspecialchars($values['address']) ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label"><?= $edit ? "New Password (leave blank to keep current)" : "Password" ?></label>
                    <input type="password" name="password" class="form-control" <?= $edit ? '' : 'required' ?>>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success"><?= $edit ? "Update" : "Add" ?> Staff</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
</body>
</html>
