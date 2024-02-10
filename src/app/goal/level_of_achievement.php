<!-- goal_check.php -->

<?php
$page_title = "目標達成度";
$stylesheet_name = "goal_check";
require_once("../include/header.php");
?>

<?php
require($absolute_path."lib/level_of_achievement_class.php");
$level_of_achievement_class = new level_of_achievement_class($db);
$have_points = $level_of_achievement_class->getHave_points();
$savings = $level_of_achievement_class->getSavings();
$have_money = $have_points+$savings;
?>

<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <div class="container">
        <h1>達成度の状況</h1>

        <p><strong>現在のお金（ポイント＋貯金）:</strong><?php echo htmlspecialchars($have_money); ?>円</p>
        <p>内訳</p>
        <p>ポイント:<strong><?php echo htmlspecialchars($have_points); ?></strong>ポイント</p>
        <p>貯金:<strong><?php echo htmlspecialchars($savings); ?></strong>円</p>

        <hr>

        <?php if (count($level_of_achievement_class->getGoal()) != 0) : ?>
            <?php for ($i = 0; $i < count($level_of_achievement_class->getGoal()); $i++) : ?>
                <p>いつまで:<strong><?php echo htmlspecialchars($level_of_achievement_class->getGoal_deadline($i)); ?></strong></p>
                <p>内容:<strong><?php echo htmlspecialchars($level_of_achievement_class->getGoal_detail($i)); ?></strong></p>
                <p>目標金額:<strong><?php echo htmlspecialchars($level_of_achievement_class->getTarget_amount($i)); ?></strong>円</p>
                <p>お小遣いが1ヶ月に1回もらえるのをふまえると合計であと<strong><?php echo htmlspecialchars($level_of_achievement_class->getRequired_point($i)); ?></strong>ポイント必要です</p>
                <p>期限までに目標金額を達成するには１日あたりあと<strong><?php echo htmlspecialchars($level_of_achievement_class->getOnerequired_point($i)); ?></strong>ポイント必要です</p>

                <hr>
            <?php endfor; ?>
        <?php else : ?>
            <p>目標を設定してください</p>
        <?php endif; ?>

        <p class="mt-3"><a href="../accounts/welcome.php" class="btn btn-primary">ホーム</a></p>
    </div>
</main>
<!-- ナビゲーションバー -->
<?php include_once("../include/bottom_nav.php") ?>
<!-- フッター -->
<?php require_once("../include/footer.php"); ?>
