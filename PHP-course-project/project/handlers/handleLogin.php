<?php
require_once('./db.php');
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

    header('Location: ../login.php');
    exit;
}

$username=trim($_POST['username']);
$password=trim($_POST['password']);
$query = "SELECT * FROM users WHERE username = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$username]);
$user = $stmt->fetch();

if(!$user){
    $error="Invalid data";
        $_SESSION['flash']['message']['type']='danger';
        $_SESSION['flash']['message']['text']=$error;
        $_SESSION['flash']['data']=$_POST;
    header('Location: ../login.php');
    exit;
}

if(!password_verify($password,$user['password'])){
    $error="Invalid data";
        $_SESSION['flash']['message']['type']='danger';
        $_SESSION['flash']['message']['text']=$error;
        $_SESSION['flash']['data']=$_POST;
    header('Location: ../login.php');
    exit;
}

session_start();
$_SESSION['username']= $user['username'];
$_SESSION['email']= $user['email'];
$_SESSION['user_id']= $user['id'];

header('Location: ../index.php');
exit;
?>