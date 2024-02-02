<?php 
require("../../../lib/havepoint_class.php");


// entryクラスのインスタンスを作成
$havepoint = new havepoint($db);
?>


<?php
$page_title = "ポイント表示";
require_once("../include/header.php");
?>

<main>
    <p>★手持ちポイント★</p>
    <?php $havepoint->display_point(); ?>

</main>

<?php require_once("../include/footer.php"); ?>
