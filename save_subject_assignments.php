<?php
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_id = $_POST['class_id'] ?? '';
    $subjects = $_POST['subjects'] ?? [];

    if (!$class_id || !is_array($subjects)) {
        die("Invalid data.");
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // 1. Delete existing subject assignments for the class
        $stmt = $conn->prepare("DELETE FROM student_subjects WHERE student_id IN (SELECT student_id FROM students WHERE class_id = ?)");
        $stmt->bind_param('i', $class_id);
        $stmt->execute();

        // 2. Re-insert new assignments
        $stmt = $conn->prepare("INSERT INTO student_subjects (student_id, subject_id) VALUES (?, ?)");
        foreach ($subjects as $student_id => $subject_ids) {
            foreach ($subject_ids as $subject_id) {
                $stmt->bind_param('ii', $student_id, $subject_id);
                $stmt->execute();
            }
        }

        // Commit transaction
        $conn->commit();

        // Redirect to dashboard after saving
        header("Location: dashboard.php?success=subjects_assigned");
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        die("Error saving subject assignments: " . $e->getMessage());
    }
} else {
    die("Invalid request.");
}
