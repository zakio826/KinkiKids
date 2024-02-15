<!-- 行動目標入力情報確認画面 -->

<!-- ヘッダー -->
<?php
$page_title = "入力情報確認";
$stylesheet_name = "behavioral_check.css";
require_once("../include/header.php");
?>



<?php
require($absolute_path."lib/behavioral_check_class.php");
$behavioral_check = new behavioral_check($db);
$behavioral_user_name = $behavioral_check->getusername(); 
?>


<?php include_once("../include/nav_bar.php") ?>

<main>
    <div class="content">
        <div class="frame_check">
            <div class="wrapper1">
                <form action="" method="POST">
                    <input type="hidden" name="check" value="checked">
                    
                    <h1><ruby>入力情報<rt>にゅうりょくじょうほう</rt></ruby>の<ruby>確認<rt>かくにん</rt></ruby></h1>
                    
                    <?php if (!empty($error) && $error === "error"): ?>
                        <p class="error">＊<ruby>行動目標登録<rt>こうどうもくひょうとうろく</rt></ruby>に<ruby>失敗<rt>しっぱい</rt></ruby>しました。</p>
                    <?php endif ?>

                    <hr>

                    <div class="control_check">
                        <p class="moji-check"><ruby>行動目標<rt>こうどうもくひょう</rt></ruby>:</p>
                        <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['behavioral_goal'], ENT_QUOTES); ?></span></p>
                    </div>
        
                    <div class="control_check">
                        <p class="moji-check"><ruby>子供<rt>こども</rt></ruby>:</p>
                        <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($behavioral_user_name, ENT_QUOTES); ?></span></p>
                    </div>

                    <div class="control_check">
                        <p class="moji-check"><ruby>報酬<rt>ほうしゅう</rt></ruby>ポイント:</p>
                        <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['reward_point'], ENT_QUOTES); ?></span></p>
                    </div>

                    <div class="control_check">
                        <p class="moji-check"><ruby>期限<rt>きげん</rt></ruby>:</p>
                        <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['behavioral_deadline'], ENT_QUOTES); ?></span></p>
                    </div>
                
                    <br>
                    
                    <a href="./setting_behavioral.php" class="btn btn-henkou"><ruby>変更<rt>へんこう</rt></ruby>する</a>
                    <br>
                    <br><!-- ここに追加 -->
                    <button type="submit" class="btn btn-touroku"><ruby>登録<rt>とうろく</rt></ruby>する</button>
                    <div class="clear"></div>
                </form>
            </div>
        </div>
    </div>
</main>
<!-- ナビゲーションバー -->
<?php include_once("../include/bottom_nav.php") ?>
<!-- フッター -->
<?php require_once("../include/footer.php"); ?>