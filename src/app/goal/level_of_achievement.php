<!-- goal_check.php -->

<?php
require("../../../config/db_connect.php");
require("../../../lib/level_of_achievement_class.php");
session_start();
$db = new connect();
$level_of_achievement_class = new level_of_achievement_class($db);
$have_points = $level_of_achievement_class->getHave_points();
$savings = $level_of_achievement_class->getSavings();
$have_money = $have_points+$savings;
$target_amount = $level_of_achievement_class->getTarget_amount();
$requiredpoint = $level_of_achievement_class->getRequired_point();
$onerequiredpoint = $level_of_achievement_class->getOnerequired_point();
unset($_SESSION['join']);
?>

<?php
$page_title = "目標達成度";
require_once("../include/header.php");
?>

<main>
    <div class="container">
    <h1>達成度の状況</h1>
        <p><strong>現在のお金（ポイント＋貯金）:</strong> <?php echo htmlspecialchars($have_money); ?> 円</p>
        <p>内訳</h3>
        <p>ポイント:</strong> <?php echo htmlspecialchars($have_points); ?> ポイント</p>
        <p>貯金:</strong> <?php echo htmlspecialchars($savings); ?> 円</p>

        <p>目標金額:</strong> <?php echo htmlspecialchars($target_amount); ?> 円</p>
        <p>必要ポイント:</strong> <?php echo htmlspecialchars($requiredpoint); ?> 円</p>
        <p>1日の必要ポイント:</strong> <?php echo htmlspecialchars($onerequiredpoint); ?> 円</p>


        <p class="mt-3">
            <a href="../index.php" class="btn btn-primary">戻る</a>
        </p>
    </div>
</main>

<?php require_once("../include/footer.php"); ?>
