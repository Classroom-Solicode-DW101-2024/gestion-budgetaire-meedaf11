<?php

if (session_status() == PHP_SESSION_NONE) {
     session_start();
}

$db_host = 'localhost';
$db_username = 'root';
$db_name = 'gestion_budget';
$db_password = '';

try {
     $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $ex) {
     die("Database Connection Probleme : " . $ex->getMessage());
}

$categories = [
     'revenu' => ['Salaire', 'Bourse', 'Ventes', 'Autres'],
     'depense' => ['Logement', 'Transport', 'Alimentation', 'Santé', 'Divertissement', 'Éducation', 'Autres']
];

putCategories($categories, $pdo);

function putCategories($categories, $connection){

     foreach ($categories as $type => $noms) {

          foreach ($noms as $nom) {

               $checkAvaillableSQL = "SELECT * FROM categories WHERE nom = :nom AND type = :type";
               $checkAvaillableStmt = $connection->prepare($checkAvaillableSQL);
               $checkAvaillableStmt->bindParam(':nom', $nom);
               $checkAvaillableStmt->bindParam(':type', $type);
               $checkAvaillableStmt->execute();
               $checkAvaillableResult = $checkAvaillableStmt->fetch(PDO::FETCH_ASSOC);

               if (!$checkAvaillableResult) {

                    $insertCategorieSQL = "INSERT INTO categories (nom,type) VALUE (:nom, :type)";
                    $insertCategorieStmt = $connection->prepare($insertCategorieSQL);
                    $insertCategorieStmt->bindParam(':nom', $nom);
                    $insertCategorieStmt->bindParam(':type', $type);
                    $insertCategorieStmt->execute();
               }
          }
     }
}
