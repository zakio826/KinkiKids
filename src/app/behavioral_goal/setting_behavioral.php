<!-- ポイントノルマ設定ページ -->

<!-- ヘッダー -->
<?php
$page_title = "行動目標設定";
$stylesheet_name = "setting_behavioral.css";
include("../include/header.php");
?>

<?php // ページの最初に行う処理
 require($absolute_path."lib/setting_behavioral_class.php");
 $setting_behavioral = new setting_behavioral($db);
 $familyId = $_SESSION['join']['family_id'];
?>


<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>  <!-- ←一番外側はmainタグを指定する -->
    <section>
        <div class="content">
            <form action="" method="POST">
                <h1>行動目標設定</h1>

                <br>

                <div class="control-1">
                    <label for="behavioral_goal">行動目標</label>
                    <input id="behavioral_goal" type="text" name="behavioral_goal" value="<?php echo isset($_SESSION['join']['behavioral_goal']) ? htmlspecialchars($_SESSION['join']['behavioral_goal'], ENT_QUOTES) : ''; ?>">
                    <?php $setting_behavioral->behavioral_error(); ?>
                </div>

                <div class="control-1">
                    <label for="behavioral_user">子供</label>
                    <select id="behavioral_user" name="behavioral_user">
                        <?php
                        // セッションから家族IDを取得
                        $familyId = $_SESSION['join']['family_id'];

                        // 家族IDに基づいてユーザーを取得
                        $familyUsers = $setting_behavioral->getFamilyUsers($familyId);
                         $new_user = array($familyUsers["user_id"] => $familyUsers["first_name"]);


                        


                        // プルダウンメニューにユーザーを表示
                        foreach ($new_user as $key => $value) {
                            echo  '<option value="' . $key . '">' . $value . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="control-1">
                    <label for="reward_point">報酬ポイント</label>
                    <input id="reward_point" type="number" name="reward_point" value="<?php echo isset($_SESSION['join']['reward_point']) ? htmlspecialchars($_SESSION['join']['reward_point'], ENT_QUOTES) : ''; ?>">
                    <b>pt</b>
                    <?php $setting_behavioral->reward_error(); ?>
                </div>

                <div class="control-1">
                    <label for="behavioral_deadline">期限</label>
                    <input id="behavioral_deadline" type="date" name="behavioral_deadline" value="<?php echo isset($_SESSION['join']['behavioral_deadline']) ? htmlspecialchars($_SESSION['join']['behavioral_deadline'], ENT_QUOTES) : ''; ?>">
                    <?php $setting_behavioral->deadline_error(); ?>
                </div>

                <br>

                <div class="control-2">
                    <button type="submit" class="btn">登録</button>
                </div>
            </form>
        </div>
    </section>
</main>
<!-- ナビゲーションバー -->
<?php include_once("./include/bottom_nav.php") ?>
<!-- フッター -->
<?php include_once("../include/footer.php"); ?>