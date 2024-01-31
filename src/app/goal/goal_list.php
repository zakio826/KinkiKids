<?php
// goal_list.php

require("./db_connect.php");
require("./goal_class.php");
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$db = new connect();
$goal = new goal($db);

// ユーザーのIDを取得
$user_id = $_SESSION["user_id"];

// ユーザーが登録した目標の情報を取得
$goals = $goal->getUserGoals($user_id);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <title>目標一覧</title>
</head>
<body>
    <div class="content">
        <h1>登録した目標一覧</h1>

        <?php if (empty($goals)): ?>
            <p>登録した目標はありません。</p>
        <?php else: ?>
            <ul>
                <?php foreach ($goals as $goal): ?>
                    <li>
                        <strong>目標金額:</strong> <?php echo $goal['target_amount']; ?> 円<br>
                        <strong>目標詳細:</strong> <?php echo $goal['goal_detail']; ?><br>
                        <strong>期限:</strong> <?php echo $goal['goal_deadline']; ?><br>
                        <strong>作成日時:</strong> <?php echo $goal['goal_created_date']; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <p class="mt-3">
            <a href="index.php" class="btn btn-primary">インデックス</a>
        </p>
    </div>
</body>
</html>
