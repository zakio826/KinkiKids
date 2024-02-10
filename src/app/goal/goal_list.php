<?php
$page_title = "目標一覧";
$stylesheet_name = "goal_list.css";
require_once("../include/header.php");
?>

<?php
// goal_list.php

require($absolute_path."lib/goal_class.php");
$goal = new goal($db);
$goal_check = new goal_check($db);

// ユーザーのIDを取得
$user_id = $_SESSION["user_id"];

// ユーザーが登録した目標の情報を取得
$goals = $goal->getUserGoals($user_id);


?>

<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <div class="content">
        <h1>もくひょういちらん</h1>

        <?php if (empty($goals)): ?>
            <p>もくひょうがないよ！</p>
        <?php else: ?>
            <ul>
                <?php foreach ($goals as $goal): ?>
                    <?php $goal_child_name = $goal_check->getchildname($db, $goal['goal_user_id']); ?> 
                    <li>
                        <strong>こども:</strong> <?php echo $goal_child_name; ?> <br>
                        <strong>もくひょうきんがく:</strong> <?php echo $goal['target_amount']; ?> 円<br>
                        <strong>しょうさい:</strong> <?php echo $goal['goal_detail']; ?><br>
                        <strong>きげん:</strong> <?php echo $goal['goal_deadline']; ?><br>
                        <strong>さくせいび:</strong> <?php echo $goal['goal_created_date']; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <p class="mt-3"><a href="../index.php" class="btn btn-primary">もどる</a></p>
    </div>
</main>
<!-- ナビゲーションバー -->
<?php include_once("../include/bottom_nav.php") ?>
<!-- フッター -->
<?php require_once("../include/footer.php"); ?>

