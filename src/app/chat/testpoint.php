<!-- ユーザー登録ページ -->

<?php
$page_title = "テストポイント";
require_once("../include/header.php");
?>

<?php 
require($absolute_path."lib/testpoint_class.php");
// entryクラスのインスタンスを作成
$testpoint = new testpoint($db);
?>

<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

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