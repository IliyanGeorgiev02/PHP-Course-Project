<?php
require_once('./db.php');
$error='';
foreach($_POST as $key => $value){
    if(mb_strlen($value)==null){
        $error='Invalid data';
        break;
    }
}
if(mb_strlen($error)>0){
    $_SESSION['flash']['message']['type']='danger';
    $_SESSION['flash']['message']['text']=$error;
    $_SESSION['flash']['data']=$_POST;
    header('Location: ../addTask.php');
    exit;
}else{
    $taskName=trim($_POST['taskName']);
    $description=trim($_POST['description']);
    $importance=trim($_POST['importance']);
    $endDate=trim($_POST['endDate']);
    $isComplete=0;
    $userId=$_SESSION['user_id'];
    if($endDate<date("Y-m-d")){
        $error='Invalid date entered';
        $_SESSION['flash']['message']['type']='danger';
        $_SESSION['flash']['message']['text']=$error;
        $_SESSION['flash']['data']=$_POST;
        header('Location: ../addTask.php');
        exit;
    }
    if($userId==null){
        $error='Invalid user data';
        $_SESSION['flash']['message']['type']='danger';
        $_SESSION['flash']['message']['text']=$error;
        $_SESSION['flash']['data']=$_POST;
        header('Location: ../addTask.php');
        exit;
    }

    $query="INSERT INTO tasks (`name`, `description`, importance, end_date, completed, user_id) 
    VALUES ('$taskName','$description','$importance','$endDate','$isComplete','$userId')";
        $stmt=$pdo->query($query);
        if($stmt){
            header('Location: ../index.php');
        }else{
            $error='Invalid data';
            $_SESSION['flash']['message']['type']='danger';
            $_SESSION['flash']['message']['text']=$error;
            $_SESSION['flash']['data']=$_POST;
            header('Location: ../addTask.php');
            exit;
        }
}
?>