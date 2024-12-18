<?php

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

include('../handlers/db.php'); 
if (isset($_POST['task_id'])) {
    $taskId = $_POST['task_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :task_id AND user_id = :user_id");
        $stmt->execute([
            ':task_id' => $taskId,
            ':user_id' => $_SESSION['user_id']
        ]);

        if ($stmt->rowCount() > 0) {
            header('Location: ../index.php');
            exit;
        } else {
            header('Location: ../index.php');
            exit;
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Bad Request: Task ID missing.']);
}
?>
