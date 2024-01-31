<?php

require("./db_connect.php");
require("./goal_class.php");
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

if (!isset($_SESSION['join'])) {
    header('Location: goal.php');
    exit();
}

$targetAmount = $_SESSION['join']['target_amount'];
$goalDetail = $_SESSION['join']['goal_detail'];
$goalDeadline = $_SESSION['join']['goal_deadline'];

unset($_SESSION['join']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>目標</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ 
            font: 14px sans-serif;
            text-align: center; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>登録された目標の内容</h1>
        <p><strong>目標金額:</strong> <?php echo htmlspecialchars($targetAmount); ?> 円</p>
        <p><strong>目標詳細:</strong> <?php echo htmlspecialchars($goalDetail); ?></p>
        <p><strong>期限:</strong> <?php echo htmlspecialchars($goalDeadline); ?></p>
        <p>以上の内容で登録しました</p>

        <p class="mt-3">
            <a href="goal_list.php" class="btn btn-primary">目標リスト</a>
        </p>
    </div>
</body>
</html>
