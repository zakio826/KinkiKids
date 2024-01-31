<!-- ユーザー登録ページ -->
<?php 
// test
require("./db_connect.php");
require("./goal_class.php");
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}


$db = new connect();
$goal = new goal($db);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <title>目標設定</title>
</head>
<body>
    <div class="content">
        <form action="" method="POST">
            <h1>目標設定</h1>
            <p>当サービスをご利用するために、次のフォームに必要事項をご記入ください。</p>
            <br>

            <div class="control">
                <label for="target_amount">目標金額</label>
                <input id="target_amount" type="int" name="target_amount">
                円
            </div>
 
            <div class="control">
                <label for="goal_detail">目標詳細</label>
                <input id="goal_detail" type="text" name="goal_detail">
            </div>

            <div class="control">
                <label for="goal_deadline">期限</label>
                <input id="goal_deadline" type="date" name="goal_deadline">
            </div>
 
            <div class="control">
                <button type="submit" class="btn">登録する</button>
            </div>
        </form>
    </div>
</body>
</html>