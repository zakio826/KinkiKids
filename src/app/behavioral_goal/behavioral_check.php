<!-- 行動目標入力情報確認画面 -->

<!-- ヘッダー -->
<?php
$page_title = "入力情報確認";
$stylesheet_name = "norma_check.css";
require_once("../include/header.php");
?>

<?php include_once("../include/nav_bar.php") ?>

<?php
require($absolute_path."lib/behavioral_check_class.php");
$behavioral_check = new behavioral_check($db);
$behavioral_user_name = $behavioral_check->getusername(); 
?>

<main>
    <div class="content">
        <div class="frame_check">
            <div class="wrapper1">
                <form action="" method="POST">
                    <input type="hidden" name="check" value="checked">
                    
                    <h1>入力情報の確認</h1>
                    
                    <?php if (!empty($error) && $error === "error"): ?>
                        <p class="error">＊行動目標登録に失敗しました。</p>
                    <?php endif ?>

                    <hr>

                    <div class="control_check">
                        <p class="moji-check">行動目標:</p>
                        <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['behavioral_goal'], ENT_QUOTES); ?></span></p>
                    </div>
        
                    <div class="control_check">
                        <p class="moji-check">子供:</p>
                        <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($behavioral_user_name, ENT_QUOTES); ?></span></p>
                    </div>

                    <div class="control_check">
                        <p class="moji-check">報酬ポイント:</p>
                        <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['reward_point'], ENT_QUOTES); ?></span></p>
                    </div>

                    <div class="control_check">
                        <p class="moji-check">期限:</p>
                        <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['behavioral_deadline'], ENT_QUOTES); ?></span></p>
                    </div>
                
                    <br>
                    
                    <a href="./setting_norma.php" class="btn back-btn">変更する</a>
                    <button type="submit" class="btn btn-primary">登録する</button>
                    <div class="clear"></div>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- フッター -->
<?php require_once("../include/footer.php"); ?>