<?php 
// test
session_start();
require("./db_connect.php");
require("./testpoint_class.php");


// データベース接続を行う
$db = new connect();

// entryクラスのインスタンスを作成
$testpoint = new testpoint($db);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <title>テストポイント</title>
</head>
<body>
    <p>★LINEテスト★</p>
    <?php $testpoint->role_select(); ?>

    <p>★SESSIONテスト★</p>
    <?php $testpoint->sessiontest(); ?>

</body>
</html>