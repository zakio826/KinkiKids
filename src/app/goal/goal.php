<!-- 目標登録ページ -->

<?php
$page_title = "目標設定";
$stylesheet_name = "goal_all.css";
require_once("../include/header.php");
?>

<?php 
require($absolute_path."lib/goal_class.php");
$goal = new goal($db);
$familyId = $_SESSION['join']['family_id'];
$select = $_SESSION["select"];
?>

<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <div class= <?php echo ($select === 'adult') ? "content_adult" : "content_child" ;?>>
        <form action="" method="POST">
            <div class=<?php echo ($select === 'adult') ? "adult_font" : "child_font" ;?>>
                <h1>こうにゅうもくひょうせってい</h1>
            </div>
            

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
                <br>
                <?php $goal->amount_error(); ?>
            </div>
 
            <div class="control-1">
                <label for="goal_detail">しょうさい</label>
                <input id="goal_detail" type="text" name="goal_detail"  placeholder="ゲームを買いたい"><br>
                <?php $goal->detail_error(); ?>
            </div>

            <div class="control-1">
                <label for="goal_deadline">きげん</label>
                <input id="goal_deadline" type="date" name="goal_deadline"><br>
                <?php $goal->deadline_error(); ?>
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
