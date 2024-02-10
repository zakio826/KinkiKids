<?php
$page_title = "再目標設定";
$stylesheet_name = "goal_adult.css";
require_once("../include/header.php");
?>

<?php 
require($absolute_path."lib/goal_class.php");
$goal = new goal($db);


require($absolute_path."lib/index_parent_class.php");
$index_parent_class = new index_parent_class($db);

foreach ($index_parent_class->getFamily() as $child) {
    $goal_deadline = $child['goal_deadline'];
    if ($index_parent_class->isDeadlinePassed($goal_deadline)) {
        // 目標の削除処理（例：目標のIDを使用してDBから目標を削除する処理）
        // ここに目標を削除する処理を記述
        $child_user_id = $child['user_id'];
        $goal->deleteGoal($child_user_id);
    }
}
?>


<main>
    <div class="content">
        <form action="" method="POST">
            <h1>こうにゅうもくひょうせってい</h1>

            <br>

            <div class="control-1">
                <label for="target_amount">きんがく</label>
                <input id="target_amount" type="int" name="target_amount"  placeholder="5,000">
                <b>円</b>
            </div>
 
            <div class="control-1">
                <label for="goal_detail">しょうさい</label>
                <input id="goal_detail" type="text" name="goal_detail"  placeholder="ゲームを買いたい">
            </div>

            <div class="control-1">
                <label for="goal_deadline">きげん</label>
                <input id="goal_deadline" type="date" name="goal_deadline">
            </div>

            <div class="mt-3 control-2">
                <button type="submit" class="btn">とうろくする</button>
            </div>
        </form>
    </div>
</main>
<!-- ナビゲーションバー -->
<?php include_once("./include/bottom_nav.php") ?>
<!-- フッター -->
<?php require_once("../include/footer.php"); ?>
