<?php

require_once '../config.php';

if (!isset($_SESSION['user'])) {
    header('Location: /MoneyManagement/login.php');
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/transaction.css">
    <title>Transaction</title>
</head>
<body class="transactionBody">
    

<aside class="leftfixAside">

    <div class="sectionBtn"><a href="/MoneyManagement/index.php">Home</a></div>
    <div class="sectionBtn"><a href="?page=dashboard">Dashboard</a></div>
    <div class="sectionBtn"><a href="?page=profile">Profile</a></div>
    <div class="sectionBtn"><a href="?page=addTransaction">Add Transaction</a></div>
    <div class="sectionBtn"><a href="?page=historique">Historique</a></div>

</aside>

<main class="content">

    <?php
    
        $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

            

        if($page === 'dashboard'){
            include 'dashboard.php';

        }elseif($page === 'addTransaction'){
            include 'addTransaction.php';

        }elseif($page === 'edit'){
            include 'editTransaction.php';

        }elseif($page === 'profile'){
            include 'profile.php';

        }elseif($page === 'historique'){
            include 'historique.php';

        }else{
            include 'dashboard.php';
        }

    ?>

</main>


</body>
</html>