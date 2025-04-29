<?php
require_once '../config.php';
include 'TransactionFunction.php';
include '../user.php';

$errors = [];

if(isset($_POST['AddTransaction'])){

  $transactionType = isset($_POST['type'])  ? $_POST['type'] : '' ;
  $transactionAmount = $_POST['amount'];
  $transactionCategory = isset($_POST['category']) ? $_POST['category'] : '';
  $transactionDescription = $_POST['description'];
  $transactionDate = $_POST['date'];


  if(empty($transactionType)){

    $errors['transType'] = 'Type is required.';

  }

  if(empty($transactionAmount)){

    $errors['transAmount'] = 'Amount is required.';

  }

  if(empty($transactionCategory)){

    $errors['transCategory'] = 'Category is required.';

  }

  if(empty($transactionDate)){

    $errors['transDate'] = 'Date is required.';

  }


  if(empty($errors)){

    $transaction= [
      'category_id' =>htmlspecialchars(getCategoryId($transactionCategory ,$transactionType,$pdo)) ,
      'montant' =>htmlspecialchars($transactionAmount) ,
      'description' =>htmlspecialchars($transactionDescription) ,
      'date_transaction' =>htmlspecialchars($transactionDate)
    ];

    addTransaction($transaction,$pdo);

  }


}


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style/addTransaction.css">
  <title>Add Transaction</title>
</head>

<div class="addTransactionBody">


  <div class="formContainer">

  <form method="POST">
    <h2>Add a transaction</h2>

    <label for="type">Transaction type</label>
    <select name="type" id="type" >
      <option value="" disabled selected>-- Choose Type --</option>
      <option value="revenu">Income</option>
      <option value="depense">Spent</option>
    </select>
    <?php if (isset($errors['transType'])): ?>
      <p><?php echo $errors['transType']; ?></p>
    <?php endif; ?>

    <label for="amount">Amount</label>
    <input type="number" name="amount" id="amount" >
    <?php if (isset($errors['transAmount'])): ?>
      <p><?php echo $errors['transAmount']; ?></p>
    <?php endif; ?>

    <label for="category">Category</label>
    <select name="category" id="category" >
      <option value="" selected disabled>-- Choose category --</option>
    </select>
    <?php if (isset($errors['transCategory'])): ?>
      <p><?php echo $errors['transCategory']; ?></p>
    <?php endif; ?>

    <label for="description">Description</label>
    <textarea name="description" id="description" placeholder="Optional description..."></textarea>

    <label for="date">Transaction date</label>
    <input type="date" name="date" id="date" >
    <?php if (isset($errors['transDate'])): ?>
      <p><?php echo $errors['transDate']; ?></p>
    <?php endif; ?>

    <button name="AddTransaction">Add Transaction</button>
  </form>

  </div>


  <script>

    let categories= <?php echo json_encode($categories)?>;
    let typeSelect = document.getElementById('type');
    let categorySelect = document.getElementById('category');

    typeSelect.addEventListener('change',()=>{

      let typeSelected = typeSelect.value;
      categorySelect.innerHTML = '<option value="">-- Choose category --</option>';

      if(categories[typeSelected]){

        categories[typeSelected].forEach(category=>{

          let option = document.createElement('option');
          option.value = category;
          option.textContent = category;
          categorySelect.appendChild(option);

        });

      }


    });
    

  </script>

</body>

</html>