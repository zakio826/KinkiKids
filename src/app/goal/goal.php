<!-- 目標登録ページ -->

<?php
$page_title = "目標設定";
$stylesheet_name = "goal_adult.css";
require_once("../include/header.php");
?>

<?php 
require($absolute_path."lib/goal_class.php");
$goal = new goal($db);
$familyId = $_SESSION['join']['family_id'];
?>

<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <div class="content">
        <form action="" method="POST">
            <h1>こうにゅうもくひょうせってい</h1>

            <br>

            <div class="control-1">
                    <label for="goal_user">こども</label>
                    <select id="goal_user" name="goal_user">
                        <?php
                        // セッションから家族IDを取得
                        $familyId = $_SESSION['join']['family_id'];

                        // 家族IDに基づいてユーザーを取得
                        list($child_id, $child_first_name) = $goal->getFamilyUsers($familyId);
                        $new_user = array_combine($child_id, $child_first_name);
                        // プルダウンメニューにユーザーを表示
                        foreach ($new_user as $key => $value) {
                            echo  '<option value="' . $key . '">' . $value . '</option>';
                        }
                        ?>
                    </select>
                </div>

            <div class="control-1">
                <label for="target_amount">きんがく</label>
                <input id="target_amount" type="number" name="target_amount"  placeholder="5,000">
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
<?php include_once("../include/bottom_nav.php") ?>
<!-- フッター -->
<?php require_once("../include/footer.php"); ?>
