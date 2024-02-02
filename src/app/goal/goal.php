<!-- 目標登録ページ -->

<?php
$page_title = "目標設定";
require_once("../include/header.php");
?>

<?php 
require("../../../lib/goal_class.php");
$goal = new goal($db);
?>

<!-- CSSファイルのリンクを追加 -->
<link rel="stylesheet" type="text/css" href="../../../static/css/goal.css">

<main>
    <div class="content">
        <form action="" method="POST">
            <h1>もくひょうせってい</h1>
            <br>

            <div class="control">
                <label for="target_amount">もくひょう</label>
                <input id="target_amount" type="int" name="target_amount"  placeholder="5,000">
                <b>円</b>
            </div>
 
            <div class="control">
                <label for="goal_detail">しょうさい</label>
                <input id="goal_detail" type="text" name="goal_detail"  placeholder="ゲームを買いたい">
            </div>

            <div class="control">
                <label for="goal_deadline">きげん</label>
                <input id="goal_deadline" type="date" name="goal_deadline">
            </div>
 
            <br>
            <div class="control1">
                <button type="submit" class="btn">とうろくする</button>
            </div>
        </form>
    </div>
</main>

<?php require_once("../include/footer.php"); ?>
