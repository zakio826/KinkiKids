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


<?php
$page_title = "ポイント表示";
require_once("../include/header.php");
?>

<main>
    <p>★手持ちポイントde-su★</p>
    <?php $havepoint->display_point(); ?>

</main>

<?php require_once("../include/footer.php"); ?>
