<!-- ノルマ入力情報確認画面 -->

<!-- ヘッダー -->
<?php
$page_title = "入力情報確認";
$stylesheet_name = "norma_check.css";
require_once("../include/header.php");
?>

<?php
require($absolute_path."lib/norma_check_class.php");
$norma_check = new norma_check($db);
?>


<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <div class="content">
        <div class="frame_check">
            <div class="wrapper1">
                <form action="" method="POST">
                    <input type="hidden" name="check" value="checked">
                    
                    <h1>入力情報の確認</h1>
                    
                    <?php if (!empty($error) && $error === "error"): ?>
                        <p class="error">＊ノルマ登録に失敗しました。</p>
                    <?php endif ?>

                    <hr>

                    <div class="control_check">
                        <p class="moji-check">ノルマ:</p>
                        <p>
                            <span class="fas fa-angle-double-right"></span>
                            <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['norma_amount'], ENT_QUOTES); ?></span>
                        </p>
                    </div>
        
                    <div class="mb-3 control_check">
                        <p class="moji-check">期限:</p>
                        <p>
                            <span class="fas fa-angle-double-right"></span>
                            <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['point_norma_deadline'], ENT_QUOTES); ?></span>
                        </p>
                    </div>
                    
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