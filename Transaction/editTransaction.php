<?php
require_once '../config.php';
include 'TransactionFunction.php';
include '../user.php';

// Check if the id exists
if (!isset($_GET['id'])) {
    die('Transaction ID is missing.');
}

$idTransaction = $_GET['id'];

$transactionDetails = getTransactionDetails($idTransaction, $pdo);
if (!$transactionDetails) {
    die('Transaction not found.');
}

$errors = [];

if (isset($_POST['EditTransaction'])) {
    $transactionType = isset($_POST['type']) ? $_POST['type'] : '';
    $transactionAmount = $_POST['amount'];
    $transactionCategory = isset($_POST['category']) ? $_POST['category'] : '';
    $transactionDescription = $_POST['description'];
    $transactionDate = $_POST['date'];

    if (empty($transactionType)) {
        $errors['transType'] = 'Type is required.';
    }
    if (empty($transactionAmount)) {
        $errors['transAmount'] = 'Amount is required.';
    }
    if (empty($transactionCategory)) {
        $errors['transCategory'] = 'Category is required.';
    }
    if (empty($transactionDate)) {
        $errors['transDate'] = 'Date is required.';
    }

    if (empty($errors)) {
        $newTransaction = [
            'id' => $idTransaction,
            'user_id' => htmlspecialchars($_SESSION['user']['id']),
            'category_id' => htmlspecialchars(getCategoryId($transactionCategory, $transactionType, $pdo)),
            'montant' => htmlspecialchars($transactionAmount),
            'description' => htmlspecialchars($transactionDescription),
            'date_transaction' => htmlspecialchars($transactionDate)
        ];

        editTransaction($idTransaction, $newTransaction, $pdo);

        header('Location: ?page=historique');
        exit();
    }
}

// Fetch all categories (assuming you already have $categories)
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style/addTransaction.css">
  <title>Edit Transaction</title>
</head>

<body>
<div class="addTransactionBody">
  <div class="formContainer">

  <form method="POST">
    <h2>Edit Transaction</h2>

    <label for="type">Transaction type</label>
    <select name="type" id="type">
      <option value="" disabled>-- Choose Type --</option>
      <option value="revenu" <?php echo (isset($transactionDetails['type']) && $transactionDetails['type'] == 'revenu') ? 'selected' : ''; ?>>Income</option>
      <option value="depense" <?php echo (isset($transactionDetails['type']) && $transactionDetails['type'] == 'depense') ? 'selected' : ''; ?>>Spent</option>
    </select>
    <?php if (isset($errors['transType'])): ?>
      <p><?php echo $errors['transType']; ?></p>
    <?php endif; ?>

    <label for="amount">Amount</label>
    <input type="number" name="amount" id="amount" value="<?php echo htmlspecialchars($transactionDetails['montant']); ?>">
    <?php if (isset($errors['transAmount'])): ?>
      <p><?php echo $errors['transAmount']; ?></p>
    <?php endif; ?>

    <label for="category">Category</label>
    <select name="category" id="category">
      <option value="" disabled>-- Choose Category --</option>
      <!-- JS will fill it -->
    </select>
    <?php if (isset($errors['transCategory'])): ?>
      <p><?php echo $errors['transCategory']; ?></p>
    <?php endif; ?>

    <label for="description">Description</label>
    <textarea name="description" id="description" placeholder="Optional description..."><?php echo htmlspecialchars($transactionDetails['description']); ?></textarea>

    <label for="date">Transaction date</label>
    <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($transactionDetails['date_transaction']); ?>">
    <?php if (isset($errors['transDate'])): ?>
      <p><?php echo $errors['transDate']; ?></p>
    <?php endif; ?>

    <button name="EditTransaction">Edit Transaction</button>
  </form>

  </div>
</div>

<script>
    let categories = <?php echo json_encode($categories); ?>;
    let typeSelect = document.getElementById('type');
    let categorySelect = document.getElementById('category');
    let oldCategory = <?php echo json_encode($transactionDetails['category_name'] ?? ''); ?>;

    function fillCategories() {
        let typeSelected = typeSelect.value;
        categorySelect.innerHTML = '<option value="">-- Choose Category --</option>';

        if (categories[typeSelected]) {
            categories[typeSelected].forEach(category => {
                let option = document.createElement('option');
                option.value = category;
                option.textContent = category;
                if (category === oldCategory) {
                    option.selected = true;
                }
                categorySelect.appendChild(option);
            });
        }
    }

    typeSelect.addEventListener('change', fillCategories);

    window.addEventListener('DOMContentLoaded', () => {
        fillCategories();
    });
</script>

</body>
</html>
