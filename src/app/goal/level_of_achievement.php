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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>目標達成度</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ 
            font: 14px sans-serif;
            text-align: center; 
        }
    </style>
</head>
<body>
    <div class="container">
    <h1>達成度の状況</h1>
    <p><strong>現在のお金（ポイント＋貯金）:</strong> <?php echo htmlspecialchars($have_money); ?> 円</p>
    <p>内訳</h3>
    <p>ポイント:</strong> <?php echo htmlspecialchars($have_points); ?> ポイント</p>
    <p>貯金:</strong> <?php echo htmlspecialchars($savings); ?> 円</p>
    <hr>
    <?php for($i=0;$i<count($level_of_achievement_class->getGoal());$i++){ ?>
        <p>いつまで:<?php echo htmlspecialchars($level_of_achievement_class->getGoal_deadline($i)); ?> </p>
        <p>内容:<?php echo htmlspecialchars($level_of_achievement_class->getGoal_detail($i)); ?> </p>
        <p>目標金額:<?php echo htmlspecialchars($level_of_achievement_class->getTarget_amount($i)); ?> 円</p>
        <p>必要ポイント:<?php echo htmlspecialchars($level_of_achievement_class->getRequired_point($i)); ?> ポイント</p>
        <p>1日の必要ポイント:<?php echo htmlspecialchars($level_of_achievement_class->getOnerequired_point($i)); ?> ポイント</p>
        <hr>
    <?php } ?>


        <p class="mt-3">
            <a href="../accounts/welcome.php" class="btn btn-primary">ホーム</a>
        </p>
    </div>
</body>
</html>
