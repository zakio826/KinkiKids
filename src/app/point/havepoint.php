<?php
$page_title = "ポイント表示";
require_once("../include/header.php");
?>

<?php 
// entryクラスのインスタンスを作成
require($absolute_path."lib/havepoint_class.php");
$havepoint = new havepoint($db);
?>

<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <p>★手持ちポイントde-su★</p>
    <?php $havepoint->display_point(); ?>

</main>
<!-- ナビゲーションバー -->
<?php include_once("../include/bottom_nav.php") ?>
<?php require_once("../include/footer.php"); ?>
