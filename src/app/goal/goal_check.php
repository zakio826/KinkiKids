<?php

require("../../../config/db_connect.php");
require("../../../lib/goal_class.php");
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: ../accounts/login.php");
    exit;
}

if (!isset($_SESSION['join'])) { //
    header('Location: ./goal.php');
    exit();
}

$targetAmount = $_SESSION['join']['target_amount'];
$goalDetail = $_SESSION['join']['goal_detail'];
$goalDeadline = $_SESSION['join']['goal_deadline'];

unset($_SESSION['join']);　//
?>

<?php
$page_title = "目標";
require_once("../include/header.php");
?>

<link rel="stylesheet" type="text/css" href="../../../static/css/goal_check.css">

<main>
    <div class="container">
        <h1>もくひょうかくにん</h1>
        <div class="mt-1">
            <strong>きんがく　</strong>
            <p><?php echo htmlspecialchars($targetAmount); ?> 円</p>
        </div>
        <div class="mt-1">
            <strong>ないよう　</strong>
            <p><?php echo htmlspecialchars($goalDetail); ?></p>
        </div>
        <div class="mt-1">
            <strong>きげん　　</strong>
            <p><?php echo htmlspecialchars($goalDeadline); ?></p>
        </div>
        <!-- <p class="msg">以上の内容で登録しました</p> -->
        <br>
        <p class="mt-2">
            <a href="goal_list.php" class="btn">目標リスト</a>
        </p>
    </div>
</main>

<?php require_once("../include/footer.php"); ?>
