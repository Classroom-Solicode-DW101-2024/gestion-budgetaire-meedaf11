<?php

function addUser($user,$connection){

    $fullName = htmlspecialchars($user['fullName']);
    $email = htmlspecialchars($user['email']);
    $password = password_hash($user['password'],PASSWORD_DEFAULT);

    $registerSql = "INSERT INTO users (nom, email, password) VALUES(:fullName,:email,:password)";
    $registerStmt = $connection-> prepare($registerSql);
    $registerStmt-> bindParam(':fullName',$fullName);
    $registerStmt-> bindParam(':email',$email);
    $registerStmt-> bindParam(':password',$password);
    $registerStmt-> execute();

    $_SESSION['user'] = $user;

    header('Location:index.php');



}

function login($email,$password,$connection){

    

}


function soldUser($connection){

    

}


function detailsUser($connection){

    

}


?>