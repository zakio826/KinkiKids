
<?php
$page_title = "目標一覧";
require_once("../include/header.php");
?>


<?php
// goal_list.php

require("../../../lib/goal_class.php");
$goal = new goal($db);

// ユーザーのIDを取得
$user_id = $_SESSION["user_id"];

// ユーザーが登録した目標の情報を取得
$goals = $goal->getUserGoals($user_id);
?>


<main>
    <div class="content">
        <h1>登録した目標一覧</h1>

        <?php if (empty($goals)): ?>
            <p>登録した目標はありません。</p>
        <?php else: ?>
            <ul>
                <?php foreach ($goals as $goal): ?>
                    <li>
                        <strong>目標金額:</strong> <?php echo $goal['target_amount']; ?> 円<br>
                        <strong>目標詳細:</strong> <?php echo $goal['goal_detail']; ?><br>
                        <strong>期限:</strong> <?php echo $goal['goal_deadline']; ?><br>
                        <strong>作成日時:</strong> <?php echo $goal['goal_created_date']; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <p class="mt-3">
            <a href="../index.php" class="btn btn-primary">インデックス</a>
        </p>
    </div>
</main>

<?php require_once("../include/footer.php"); ?>

