<?php

require_once '../config.php';
include 'TransactionFunction.php';



$year = isset($_POST['year']) ? $_POST['year'] : '';
$month = isset($_POST['month']) ? $_POST['month'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['filter'])) {
    $listOfTransaction = listTransactionsbyMonth($pdo, $year, $month);
} else {
    $listOfTransaction = listTransactions($pdo); 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_id'])) {
        $deleteId = $_POST['delete_id'];
        
        deleteTransaction($deleteId,$pdo);

        header('Location: ?page=historique');
        exit();
    }

    if (isset($_POST['edit_id'])) {
        $editId = (int)$_POST['edit_id'];

        header('Location: ?page=edit&id=' . $editId);
        exit();
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction History</title>
    <link rel="stylesheet" href="../style/historique.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="transactionDashboard">
    <h2 class="pageTitle">Transaction History</h2>
    <div class="filterSection">
        <form method="post" style="margin-bottom: 20px;">
            <label for="year">Year:</label>
            <select name="year" id="year">
                <option value="" selected>-- Choose Year --</option>
                <?php for ($i = 2020; $i <= date("Y"); $i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo ($i == $year) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>

            <label for="month">Month:</label>
            <select name="month" id="month">
                <option value="" selected>-- Choose Month --</option>
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>" <?php echo (str_pad($i, 2, '0', STR_PAD_LEFT) == $month) ? 'selected' : ''; ?>>
                        <?php echo date("F", mktime(0, 0, 0, $i, 10)); ?>
                    </option>
                <?php endfor; ?>
            </select>

            <button type="submit" name="filter" class="filterButton">Filter</button>
        </form>
    </div>

    <table class="transactionTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Transaction Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listOfTransaction as $transaction): ?>
                <tr>
                    <td><?php echo htmlspecialchars($transaction['id']); ?></td>
                    <td><?php echo htmlspecialchars($transaction['montant']); ?> DH</td>
                    <td><?php echo htmlspecialchars($transaction['description']); ?></td>
                    <td><?php echo htmlspecialchars($transaction['date_transaction']); ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="edit_id" value="<?php echo $transaction['id']; ?>">
                            <button type="submit" class="actionButton editButton">
                                <i class="fas fa-pen-to-square"></i>
                            </button>
                        </form>

                        <form method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this transaction?');">
                            <input type="hidden" name="delete_id" value="<?php echo $transaction['id']; ?>">
                            <button type="submit" class="actionButton deleteButton">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>