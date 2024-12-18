<?php
session_start();
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

    header('Location: ../register.php?page=register');
    exit;
}else{
    $firstName=trim($_POST['firstName']);
    $lastName=trim($_POST['lastName']);
    $username=trim($_POST['username']);
    $email=trim($_POST['email']);
    $password=trim($_POST['password']);
    $repeat_password=trim($_POST['confirmPassword']);

    $query="SELECT * FROM users WHERE email=?";
    $stmt=$pdo ->prepare($query);
    $stmt->execute([$email]);
    $user=$stmt->fetch();
    if($user){
        $error="Invalid data";
        $_SESSION['flash']['message']['type']='danger';
        $_SESSION['flash']['message']['text']=$error;
        $_SESSION['flash']['data']=$_POST;
        header('Location: ../register.php?page=register');
        exit;
    }
    if($password!=$repeat_password){
        $error='Invalid data';
        $_SESSION['flash']['message']['type']='danger';
        $_SESSION['flash']['message']['text']=$error;
        $_SESSION['flash']['data']=$_POST;

        header('Location: ../register.php?page=register');
        exit;
    }

    else{
        $password=password_hash($password,PASSWORD_ARGON2I);
        $query="INSERT INTO users (first_name, last_name, username, email, `password`) VALUES ('$firstName','$lastName','$username','$email','$password')";
        $stmt=$pdo->query($query);
        if($stmt){
            $_SESSION['flash']['message']['type']='success';
            $_SESSION['flash']['message']['text']="Успешна регистрация";
            header('Location: ../login.php');
        }else{
            $error='Invalid data';
            $_SESSION['flash']['message']['type']='danger';
            $_SESSION['flash']['message']['text']=$error;
            $_SESSION['flash']['data']=$_POST;

            header('Location: ../register.php?page=register');
            exit;
    }   
    }
}
?>