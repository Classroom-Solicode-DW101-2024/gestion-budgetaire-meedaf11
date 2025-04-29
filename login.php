<?php

require 'config.php';
include 'user.php';

$errors = [];

if(isset($_SESSION['user'])){

    header('Location:index.php');
}

if(isset($_POST['logBtn'])){

    $user_email = $_POST['email'];
    $user_password = $_POST['password'];

    if(empty($user_email)){

        $errors['mailError'] = 'Email address is required.';

    }

    if(empty($user_password)){

        $errors['passwordError'] = 'Password is required.';

    }

    if(empty($errors)){

        $userInformation = login($user_email,$user_password,$pdo);

        if($userInformation){

            $_SESSION['user'] = $userInformation;
            header('Location:index.php');


        }else{
            $errors['login'] = 'Incorrect email or password.';
        }


    }


}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/login.css">
    <title>Document</title>
</head>
<body>


<div class="formContainer">
        <h2>Login Now</h2>
        <form method="post">

            <input type="email" placeholder="Email" name="email" id="loginEmail" value="<?php echo isset($User_email) ? htmlspecialchars($User_email) : ''; ?>">
            <?php if (isset($errors['mailError'])): ?>
                <p><?php echo $errors['mailError']; ?></p>
            <?php endif; ?>
            <input type="password" name="password" id="loginPassword" placeholder="Password">
            <?php if (isset($errors['passwordError'])): ?>
                <p><?php echo $errors['passwordError']; ?></p>
            <?php endif; ?>
            <?php if (isset($errors['login'])): ?>
                <p><?php echo $errors['login']; ?></p>
            <?php endif; ?>
            
            <button name="logBtn">Login</button>
        </form>
        <a href="register.php">Don't Have an Acoount ? Register</a>
    </div>

    
</body>
</html>