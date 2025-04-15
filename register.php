<?php

require 'config.php';
include 'user.php';



if(isset($_POST['regBtn'])){

    $User_fullName = $_POST['fullName'];
    $User_email = $_POST['email'];
    $User_password = $_POST['password'];
    $User_confirmPassword = $_POST['conPassword'];
    $errors = [];

    if (empty($User_fullName)) {
        $errors['fullName'] = 'Full name is required.';
    }
    
    if (empty($User_email)) {
        $errors['email'] = 'Email address is required.';
    }else{
        $isEmailAvaillable = checkUser($User_email,$pdo);
        if($isEmailAvaillable){
            $errors['email'] = 'This email address is already in use. Please choose a different one.';
        }
    }
    
    if (empty($User_password)) {
        $errors['password'] = 'Password is required.';
    }
    
    if (empty($User_confirmPassword)) {
        $errors['ConPassword'] = 'Password confirmation is required.';
    }
    
    if (!empty($User_password) && !empty($User_confirmPassword) && $User_password !== $User_confirmPassword) {
        $errors['passwordMatch'] = 'Password and confirmation do not match.';
    }

    if(empty($errors)){

        $user = [
            'fullName' => $User_fullName,
            'email' => $User_email,
            'password' => $User_password,
        ];

        addUser($user,$pdo);

    }

    

}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/register.css">
    <title>Register</title>
</head>
<body>

    <div class="formContainer">
        <h2>Register Now</h2>
        <form method="post">

            <input type="text" placeholder="Full Name" name="fullName" id="registerFullName" value="<?php echo isset($User_fullName) ? htmlspecialchars($User_fullName) : ''; ?>">
            <?php if (isset($errors['fullName'])): ?>
                <p><?php echo $errors['fullName']; ?></p>
            <?php endif; ?>
            <input type="email" placeholder="Email" name="email" id="registerEmail" value="<?php echo isset($User_email) ? htmlspecialchars($User_email) : ''; ?>">
            <?php if (isset($errors['email'])): ?>
                <p><?php echo $errors['email']; ?></p>
            <?php endif; ?>
            <input type="password" name="password" id="registerPassword" placeholder="Password" value="<?php echo isset($User_password) ? htmlspecialchars($User_password) : ''; ?>">
            <?php if (isset($errors['password'])): ?>
                <p><?php echo $errors['password']; ?></p>
            <?php endif; ?>
            <input type="password" name="conPassword" id="registerConfPassword" placeholder="Confirm Password" value="<?php echo isset($User_confirmPassword) ? htmlspecialchars($User_confirmPassword) : ''; ?>">
            <?php if (isset($errors['ConPassword'])): ?>
                <p><?php echo $errors['ConPassword']; ?></p>
            <?php endif; ?>
            <?php if (isset($errors['passwordMatch'])): ?>
                <p><?php echo $errors['passwordMatch']; ?></p>
            <?php endif; ?>
            <button name="regBtn">Register</button>
        </form>
        <a href="login.php">Have an Acoount ? Login</a>
    </div>
    
</body>
</html>