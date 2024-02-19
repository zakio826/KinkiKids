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
        <h1><ruby>目標一覧<rt>もくひょういちらん</rt></ruby></h1>
        <?php if (empty($goals)): ?>
            <p>もくひょうがないよ！</p>
        <?php else: ?>
            <ul class ="syousaizentai">
                <?php foreach ($goals as $goal): ?>
                    <?php $goal_child_name = $goal_check->getchildname($db, $goal['goal_user_id']); ?> 
                    <li class="syousai">
                        <strong><ruby>子供<rt>こども</rt></ruby>:</strong> <?php echo $goal_child_name; ?> <br>
                        <strong><ruby>目標金額<rt>もくひょうきんがく</rt></ruby>:</strong> <?php echo $goal['target_amount']; ?> 円<br>
                        <strong><ruby>詳細<rt>しょうさい</rt></ruby>:</strong> <?php echo $goal['goal_detail']; ?><br>
                        <strong><ruby>期限<rt>きげん</rt></ruby>:</strong> <?php echo $goal['goal_deadline']; ?><br>
                        <strong><ruby>作成日<rt>さくせいび</rt></ruby>:</strong> <?php echo $goal['goal_created_date']; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <p class="mt-3"><a href="../index.php" class="btn btn-primary"><ruby>戻る<rt>もどる</rt></ruby></a></p>
    </div>
</main>
<!-- ナビゲーションバー -->
<?php include_once("../include/bottom_nav.php") ?>
<!-- フッター -->
<?php include_once("../include/footer.php"); ?>

