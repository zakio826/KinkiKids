<?php

require("../../../config/db_connect.php");
require("../../../lib/goal_class.php");
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: ../accounts/login.php");
    exit;
}

if (!isset($_SESSION['join'])) {
    header('Location: ./goal.php');
    exit();
}

$targetAmount = $_SESSION['join']['target_amount'];
$goalDetail = $_SESSION['join']['goal_detail'];
$goalDeadline = $_SESSION['join']['goal_deadline'];

unset($_SESSION['join']);
?>

<?php
$page_title = "目標";
require_once("../include/header.php");
?>

<main>
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
</main>

<?php require_once("../include/footer.php"); ?>
