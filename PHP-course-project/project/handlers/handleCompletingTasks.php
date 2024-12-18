<?php
include_once('./db.php');

if(isset($_SESSION['user_id']) && isset($_POST['task_id'])){
$taskId=$_POST['task_id'];
$userId=$_SESSION['user_id'];
$stmt=$pdo->prepare("UPDATE tasks SET completed = 1 WHERE id = :task_id");
$stmt->execute([
    ':task_id' => $taskId
]);
header('Location: ../index.php');
exit;
}
?>