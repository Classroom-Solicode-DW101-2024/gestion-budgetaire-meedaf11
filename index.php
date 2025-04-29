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

<section class="hero">
  <h2>Take Control of Your Money</h2>
  <p>Track expenses, create budgets, and reach your financial goals with ease.</p>
  <a href="Transaction/" class="btn">Get Started</a>
</section>

<section id="features" class="features">
  <div class="feature">
    <h3>Track Your Spending</h3>
    <p>Monitor every transaction and understand where your money goes.</p>
  </div>
  <div class="feature">
    <h3>Smart Budgeting</h3>
    <p>Create and manage budgets tailored to your lifestyle and goals.</p>
  </div>
  <div class="feature">
    <h3>Financial Reports</h3>
    <p>View easy-to-understand charts and summaries of your finances.</p>
  </div>
</section>

<footer id="contact">
  <p>&copy; <?php echo date("Y"); ?> MoneyMate. All rights reserved.</p>
</footer>



</body>
</html>
