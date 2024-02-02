<!-- ユーザー登録ページ -->
<?php 
require("../../../lib/testpoint_class.php");


// entryクラスのインスタンスを作成
$testpoint = new testpoint($db);
?>

<?php
$page_title = "テストポイント";
require_once("../include/header.php");
?>

<main>
    <p>★LINEテスト★</p>
    <?php $testpoint->role_select(); ?>

    <p>★SESSIONテスト★</p>
    <?php $testpoint->sessiontest(); ?>

    <p>★持ちポイント★</p>
    <p>まだ</p>
    <!-- <?php $testpoint->role_select(); ?> -->
</main>

<?php require_once("../include/footer.php"); ?>