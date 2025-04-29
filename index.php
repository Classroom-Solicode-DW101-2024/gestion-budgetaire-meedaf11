<?php
require 'config.php';
include 'user.php';

if (!isset($_SESSION['user'])) {
    header('Location:login.php');
    exit();
}



if (isset($_POST['logout'])) {
    session_unset();  
    session_destroy(); 
    header("Location: login.php");
    exit();
}

// corrected: use 'nom' instead of 'fullName'
$userName = $_SESSION['user']['nom'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Management</title>
    <link rel="stylesheet" href="style/index.css">
    
       
</head>
<body>

<header>
    <div class="site-name">Money Management</div>
    <div class="user-info">
        <span><?php echo htmlspecialchars($userName); ?></span>
        <a href="Transaction/" class="dashboard-btn">Dashboard</a>
        <form method="post" class="logout-form">
            <button type="submit" name="logout" class="dashboard-btn" style="background-color:#e74c3c;">Logout</button>
        </form>
    </div>
</header>


</body>
</html>
