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

function checkUser($email,$connection){

    $isAvailableEmail = false;

    $email = htmlspecialchars($email);
    $checkSql = "SELECT * FROM `users` WHERE email = :email";
    $checkStmt = $connection-> prepare($checkSql);
    $checkStmt-> bindParam(':email',htmlspecialchars($email));
    $checkStmt->execute();
    $checkResult = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if(!empty($checkResult)){

        $isAvailableEmail = true;

    }

    return $isAvailableEmail;

}

function login($email,$password,$connection){

    $mail = htmlspecialchars($email);
    $pass = password_hash($password,PASSWORD_DEFAULT);

    $loginSql = "SELECT * FROM `users` WHERE email = :email";
    $loginStmt = $connection-> prepare($loginSql);
    $loginStmt-> bindParam(':email',$mail);
    $loginStmt->execute();
    $LoginResult = $loginStmt->fetch(PDO::FETCH_ASSOC);

    if(!empty($LoginResult) && password_verify($password,$LoginResult['password'])){
        return $LoginResult;
    }else{
        return false;
    }

    
    

}


function soldUser($connection){

    

}


function detailsUser($connection){

    

}


?>