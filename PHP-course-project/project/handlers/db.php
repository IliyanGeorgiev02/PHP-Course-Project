<?php
try {
$host = '127.0.0.1';
$db   = 'taskManager';
$user = 'root';
$pass = 'Tebeshir6969';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //как се показват грешките
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //как да се връщат данните от заявката
    PDO::ATTR_EMULATE_PREPARES   => false, //как да се подготвят заявките преди изпълняване 
];
$pdo = new PDO($dsn, $user, $pass, $options);
}catch(Exception $e){
    echo"Message: ".$e->getMessage(),"\n";
    exit();
}
?>