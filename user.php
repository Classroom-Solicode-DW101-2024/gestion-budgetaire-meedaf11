<?php

function addUser($user,$connection){

    $fullName = htmlspecialchars($user['nom']);
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

    $userEmail = $_SESSION['user']['email'];

    $userSql = "SELECT * FROM users WHERE email = :email";
    $userStmt = $connection->prepare($userSql);
    $userStmt->bindParam(':email',$userEmail);
    $userStmt->execute();
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION['user']['id'] = $user['id'];

}



function updateUser($connection, $newUserInfo)
{
    $fullName = htmlspecialchars($newUserInfo['nom']);
    $email = htmlspecialchars($newUserInfo['email']);
    $password = $newUserInfo['password'];
    $confirmPassword = $newUserInfo['confirm_password'];

    $userId = $_SESSION['user']['id'];
    $currentEmail = $_SESSION['user']['email'];

    // تحقق إذا أراد تغيير البريد الإلكتروني
    if ($email !== $currentEmail) {
        if (checkUser($email, $connection)) {
            return "This email is already in use. Please choose another one.";
        }
    }

    // إنشاء كود التحديث
    $updateSql = "UPDATE users SET nom = :fullName, email = :email";

    if (!empty($password) && !empty($confirmPassword)) {
        if ($password !== $confirmPassword) {
            return "Passwords do not match.";
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $updateSql .= ", password = :password";
    }

    $updateSql .= " WHERE id = :id";

    $updateStmt = $connection->prepare($updateSql);

    $updateStmt->bindParam(':fullName', $fullName);
    $updateStmt->bindParam(':email', $email);
    if (!empty($password) && !empty($confirmPassword)) {
        $updateStmt->bindParam(':password', $hashedPassword);
    }
    $updateStmt->bindParam(':id', $userId, PDO::PARAM_INT);

    $updateStmt->execute();

    $_SESSION['user']['nom'] = $fullName;
    $_SESSION['user']['email'] = $email;

    return true;
}

?>


