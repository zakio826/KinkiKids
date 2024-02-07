<!-- トップページ画面 -->

<!-- ヘッダー -->
<?php
$page_title = "トップページ";
include("../include/header.php");
?>

<?php

// testpointクラスのインスタンスを作成
require($absolute_path."lib/testpoint_class.php");
$testpoint = new testpoint($db);

require($absolute_path."lib/index_child_class.php");
$index_child_class = new index_child_class($db);
$have_points = $index_child_class->getHave_points();
$savings = $index_child_class->getSavings();
$have_money = $have_points+$savings;
$goal_count = $index_child_class->getGoalCount();
$help_count = $index_child_class->getHelpCount();
?>

<style>
    .action-btn {
        background-color: lemonchiffon;
        border-radius: 2rem;
        box-shadow: 0 6px 8px 0 rgba(0, 0, 0, .5);
        /* height: 30%; */
    }
</style>

<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <!-- ロゴ -->
    <header class="position-relative h-25" style="padding-top: 4rem;">
        <img class="d-block mx-auto py-3" src="<?php echo $absolute_path; ?>static/assets/logo.png" height="120">
    </header>
    
    <section class="position-relative h-75">
        <p>目標詳細</p>
        <?php if ($goal_count != 0) : ?>
            <p>もくひょう:<?php echo htmlspecialchars($index_child_class->getGoal_detail()); ?></p>
            <p>いつまで:<?php echo htmlspecialchars($index_child_class->getGoal_deadline()); ?> </p>
            <p>目標金額:<?php echo htmlspecialchars($index_child_class->getTarget_amount()); ?> 円</p>
            <p>お小遣いが1ヶ月に1回もらえるのをふまえると合計であと<?php echo htmlspecialchars($index_child_class->getRequired_point()); ?> ポイント必要です</p>
            <p>期限までに目標金額を達成するには１日あたりあと<?php echo htmlspecialchars($index_child_class->getOnerequired_point()); ?> ポイント必要です</p>
            <hr>
        <?php else : ?>
            <p>目標を設定してください</p>
        <?php endif; ?>
    </section>
    <a href="../">トップページに戻る</a>
</main>

<!-- フッター -->
<?php include_once("../include/footer.php"); ?>