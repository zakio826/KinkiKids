<?php 
// test
session_start();
require("../../../config/db_connect.php");
require("../../../lib/havepoint_class.php");


// データベース接続を行う
$db = new connect();

// entryクラスのインスタンスを作成
$havepoint = new havepoint($db);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <title>ポイント表示</title>
</head>
<body>
    <p>★手持ちポイント★</p>
    <?php $havepoint->display_point(); ?>

</body>
</html>