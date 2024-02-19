<?php
include("../include/header.php");
require_once($absolute_path."lib/functions.php");

try {
    $db = $_GET['db'];
    $id = $_GET['id'];

    switch ($db){
        case 'spend':
        case 'income':
            $sql = 'DELETE FROM income_expense_category WHERE income_expense_category_id = :id';
            break;
        case 'payment':
            $sql = 'DELETE FROM payment WHERE payment_id = :id';
            break;
    }

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        echo "データが正常に削除されました。";
    } else {
        echo "データの登録中にエラーが発生しました。";
    }
    header("Location: ".$absolute_path."src/app/spending/item-edit.php"); exit;

    } catch (PDOException $e) {
    echo "データの登録に発生しました。";
    header("Location: ".$absolute_path."src/app/spending/item-edit.php"); exit;

}

?>
