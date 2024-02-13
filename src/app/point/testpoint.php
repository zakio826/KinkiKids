<?php
$page_title = "テストポイント";
require_once("../include/header.php");
?>

<?php 
// entryクラスのインスタンスを作成
require($absolute_path."lib/testpoint_class.php");
$testpoint = new testpoint($db);
?>

<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <p>★LINEテスト★</p>
    <?php $testpoint->role_select(); ?>

    <p>★SESSIONテスト★</p>
    <?php $testpoint->sessiontest(); ?>

</main>
<!-- ナビゲーションバー -->
<?php include_once("./include/bottom_nav.php") ?>
<?php require_once("../include/footer.php"); ?>