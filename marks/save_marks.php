<?php
require_once('../db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("INSERT INTO student_marks 
        (student_id, subject_id, class_id, term, year, marks_obtained, max_marks, exam_date)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "iiisidds",
        $_POST['student_id'],
        $_POST['subject_id'],
        $_POST['class_id'],
        $_POST['term'],
        $_POST['year'],
        $_POST['marks_obtained'],
        $_POST['max_marks'],
        $_POST['exam_date']
    );

    if ($stmt->execute()) {
        echo "<script>alert('Marks saved successfully!'); window.location.href='add_marks.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
