<?php



function getCategoryId($nom , $type, $connection){

    $sql = "SELECT id from categories WHERE nom = :nom AND type = :type";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':nom',$nom);
    $stmt->bindParam(':type',$type);
    $stmt->execute();
    $id = $stmt->fetch(PDO::FETCH_ASSOC);

    
    return $id['id'];

}

function addTransaction($transaction,$connection){

    $userID = $_SESSION['user']['id'];
    $transactionCategory = $transaction['category_id'];
    $transactionMontant = $transaction['montant'];
    $transactionDescription = $transaction['description'];
    $transactionDate = $transaction['date_transaction'];

    $transactionSQL = "INSERT INTO transactions (user_id,category_id,montant,description,date_transaction) VALUE(:userId,:transactionCategory,:transactionMontant,:transactionDescription,:transactionDate)";
    $transactionStmt = $connection->prepare($transactionSQL);
    $transactionStmt-> bindParam(':userId', $userID);
    $transactionStmt-> bindParam(':transactionCategory',$transactionCategory);
    $transactionStmt-> bindParam(':transactionMontant',$transactionMontant);
    $transactionStmt-> bindParam(':transactionDescription',$transactionDescription);
    $transactionStmt-> bindParam(':transactionDate',$transactionDate);
    $transactionStmt->execute();

}

function getTotalPriceByCategory($connection, $categoryName , $categoryType){

    $userId = $_SESSION['user']['id'];

    $getTotalSql = "SELECT SUM(t.montant) AS total_price FROM transactions t INNER JOIN categories c ON t.category_id = c.id INNER JOIN users u on t.user_id = u.id WHERE u.id = :userid AND c.nom = :categoryName and c.type = :categoryType";
    $getTotalStmt = $connection->prepare($getTotalSql);
    $getTotalStmt-> bindParam(':userid', $userId);
    $getTotalStmt-> bindParam(':categoryName',$categoryName);
    $getTotalStmt-> bindParam(':categoryType',$categoryType);
    $getTotalStmt-> execute();  
    $total = $getTotalStmt->fetch(PDO::FETCH_ASSOC);

    $total = $total['total_price'];

    if($total == null){
        $total = 0;
    }

    return $total;

}

function getTotalPrice($connection, $type){

    $userId = $_SESSION['user']['id'];
    $incomeTotalSql ="SELECT SUM(t.montant) AS totalIncome FROM transactions t 
INNER JOIN categories c ON t.category_id = c.id 
INNER JOIN users u ON t.user_id = u.id 
WHERE u.id = :userId and c.type = :typeEnum ";
    $incomeTotalStmt = $connection->prepare($incomeTotalSql);
    $incomeTotalStmt -> bindParam(':userId',$userId);
    $incomeTotalStmt -> bindParam(':typeEnum',$type);
    $incomeTotalStmt -> execute();
    $totalIncome = $incomeTotalStmt->fetch(PDO::FETCH_ASSOC);

    $total = $totalIncome['totalIncome'];

    if($total == null){
        $total = 0;
    }

    return $total;

    

}  

function getCurrMonthTotalPrice($connection,$type){

    
    $userId = $_SESSION['user']['id'];
    $currentMonth = Date('m');
    $currentYear = Date('Y');

    $currDateSql = "SELECT SUM(t.montant) AS totalMonth FROM transactions t 
        INNER JOIN categories c ON t.category_id = c.id 
        INNER JOIN users u ON t.user_id = u.id 
        WHERE u.id = :userID and c.type = :typeEnum and MONTH(T.date_transaction) = :thisMonth AND YEAR(t.date_transaction) = :thisYear";

    $currDateStmt = $connection -> prepare($currDateSql);
    $currDateStmt -> bindParam(":userID",$userId);
    $currDateStmt -> bindParam(":typeEnum",$type);
    $currDateStmt -> bindParam(":thisMonth",$currentMonth);
    $currDateStmt -> bindParam(":thisYear",$currentYear);
    $currDateStmt -> execute();
    $total = $currDateStmt-> fetch(PDO::FETCH_ASSOC);

    $total = $total['totalMonth'];

    if($total == null){
        $total = 0;
    }

    return $total;

}

function getCurrMonthMaxCategory($connection, $type) {

    $userId = $_SESSION['user']['id'];
    $currentMonth = date('m');
    $currentYear = date('Y');

    $sql = "SELECT c.nom AS categoryName, t.montant
            FROM transactions t
            INNER JOIN categories c ON t.category_id = c.id
            WHERE t.user_id = :userID 
              AND c.type = :typeEnum
              AND MONTH(t.date_transaction) = :thisMonth 
              AND YEAR(t.date_transaction) = :thisYear
            ORDER BY t.montant DESC
            LIMIT 1";

    $stmt = $connection->prepare($sql);
    $stmt->bindParam(":userID", $userId);
    $stmt->bindParam(":typeEnum", $type);
    $stmt->bindParam(":thisMonth", $currentMonth);
    $stmt->bindParam(":thisYear", $currentYear);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $maxMontant = $result['montant'] ?? 0;
    $categoryName = $result['categoryName'] ?? null;

    return [
        'maxMontant' => $maxMontant,
        'categoryName' => $categoryName
    ];
}

function listTransactions($connection){

    $userId = $_SESSION['user']['id'];
    $listSql = "SELECT t.* FROM transactions t
        INNER JOIN users u on t.user_id = u.id
        WHERE u.id = :userID";
    $listStmt = $connection->prepare($listSql);
    $listStmt-> bindParam(':userID' , $userId);    
    $listStmt-> execute();
    $listTransaction = $listStmt->fetchAll(PDO::FETCH_ASSOC);

    return $listTransaction;
}


function listTransactionsbyMonth($connection, $year, $month) {
    
    $userId = $_SESSION['user']['id'];
    $sql = "SELECT * FROM transactions WHERE YEAR(date_transaction) = :year AND MONTH(date_transaction) = :month AND user_id = :userid ORDER BY date_transaction DESC";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':year', $year);
    $stmt->bindParam(':month', $month);
    $stmt->bindParam(':userid', $userId);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function deleteTransaction($idTransaction,$connection){

    $deleteSql = "DELETE FROM transactions WHERE id = :id";
    $deleteStmt = $connection->prepare($deleteSql);
    $deleteStmt->bindParam(':id', $idTransaction);
    $deleteStmt->execute();

}

function getTransactionDetails($idTransaction, $pdo){

        $sql = "SELECT t.id, t.montant, t.description, t.date_transaction, c.nom AS category_name, c.type
            FROM transactions t
            JOIN categories c ON t.category_id = c.id
            WHERE t.id = :id LIMIT 1";
    
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $idTransaction, PDO::PARAM_INT);
        $stmt->execute();
    
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $transaction ? $transaction : null;
    

}



function editTransaction($idTransaction, $newTransaction, $connection) {
    
    $userId = $_SESSION['user']['id'];

    $sql = " UPDATE transactions 
        SET user_id = :user_id,category_id = :category_id,montant = :montant, description = :description, date_transaction = :date_transaction
        WHERE id = :id
    ";

    $stmt = $connection->prepare($sql);

    
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':category_id', $newTransaction['category_id']);
    $stmt->bindParam(':montant', $newTransaction['montant']); 
    $stmt->bindParam(':description', $newTransaction['description']);
    $stmt->bindParam(':date_transaction', $newTransaction['date_transaction']);
    $stmt->bindParam(':id', $idTransaction );

    
    $stmt->execute();

}


?>