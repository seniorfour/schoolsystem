<?php
function logActivity($conn, $user_id, $action_type, $table, $record_id, $description) {
    $stmt = $conn->prepare("INSERT INTO activity_logs (user_id, action_type, table_affected, record_id, description) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issis", $user_id, $action_type, $table, $record_id, $description);
    $stmt->execute();
    $stmt->close();
}
