<?php
require_once '../config.php';
include 'TransactionFunction.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/dashboard.css">
    <title>Document</title>
</head>
<div class="transactionDashboard">

    <div class="dashboardStat">
        <div class="card">
                <h3>Disponible Actuel</h3>
                <p><?=getTotalPrice($pdo,'revenu') - getTotalPrice($pdo,'depense') ?> DH</p>
            </div>
            <div class="card">
                <h3>Total des Revenus</h3>
                <p><?=getTotalPrice($pdo,'revenu')?>DH</p>
            </div>
            <div class="card">
                <h3>Total des Dépenses</h3>
                <p><?=getTotalPrice($pdo,'depense')?> DH</p> 
        </div>
    </div>

    <div class="categoriesSection">

    <div class="incomeCategories">

        <h3>Catégories des Revenus</h3>

        <?php foreach ($categories['revenu'] as $category):?>

            <div class="categoryCard">
                <h4><?=$category?></h4>
                <p><?=getTotalPriceByCategory($pdo, $category,'revenu')?></p>
            </div>

        <?php endforeach;?>

    </div>

    <div class="expenseCategories">

        <h3>Catégories des Dépenses</h3>

        <?php foreach ($categories['depense'] as $category):?>

            <div class="categoryCard">
                <h4><?=$category?></h4>
                <p><?=getTotalPriceByCategory($pdo, $category,'depense')?></p>
            </div>

        <?php endforeach;?>
    </div>

</div>



<div class="thisMonthStats">
    <div class="thisMonthStatsHeader">
        <?php
        $currentMonthYear = date('n/Y');
        ?>
        <h3>This Month Stat (<?=$currentMonthYear?>)</h3>
    </div>
    <div class="monthCards">
        <div class="monthCard">
            <h3>Total des Revenus</h3>
            <p><?=getCurrMonthTotalPrice($pdo,'revenu')?> DH</p>
        </div>
        <div class="monthCard">
            <h3>Total des Dépenses</h3>
            <p><?=getCurrMonthTotalPrice($pdo,'depense')?> DH</p>
        </div>
    </div>

    <div class="monthCards">
        <?php 
        $revenuMax = getCurrMonthMaxCategory($pdo,'revenu');
        $depenseMax = getCurrMonthMaxCategory($pdo,'depense');
        ?>
        <div class="monthCard">
            <h3>Revenus plus haute</h3>
            <span><?=$revenuMax['categoryName']?></span>
            <p><?=$revenuMax['maxMontant']?> DH</p>
        </div>
        <div class="monthCard">
            <h3>Dépense plus haute</h3>
            <span><?=$depenseMax['categoryName']?></span>
            <p><?=$depenseMax['maxMontant']?> DH</p>
        </div>
    </div>
</div>




</div>

</html>