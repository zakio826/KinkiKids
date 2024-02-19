<!-- サインアップ入力情報確認画面 -->

<!-- ヘッダー -->
<?php
$page_title = "入力情報確認";
$stylesheet_name = "login.css";
require_once("../include/header.php");
?>

<?php
require($absolute_path."lib/check_class.php");
checkUser($db, $_SESSION['join']);
?>

<main>
    <div class="content">
        <div class="frame_check">
            <div class="wrapper1">
                <form action="" method="POST">
                    <input type="hidden" name="check" value="checked">
                    
                    <h1><ruby>入力情報<rt>にゅうりょくじょうほう</rt></ruby>の<ruby>確認<rt>かくにん</rt></ruby></h1>
                    <p>ご<ruby>入力情報<rt>にゅうりょくじょうほう</rt></ruby>に<ruby>変更<rt>へんこう</rt></ruby>が<ruby>必要<rt>ひつよう</rt></ruby>な<ruby>場合<rt>ばあい</rt></ruby>、<br><ruby>下<rt>した</rt></ruby>のボタンを<ruby>押<rt>お</rt></ruby>し、<ruby>変更<rt>へんこう</rt></ruby>を<ruby>行<rt>おこな</rt></ruby>ってください。</p>
                    <p><ruby>登録情報<rt>とうろくじょうほう<rt></ruby>はあとから<ruby>変更<rt>へんこう</rt></ruby>することもできます。</p>
                    
                    <?php if (!empty($error) && $error === "error"): ?>
                        <p class="error">＊<ruby>会員登録<rt>かいいんとうろく</rt></ruby>に<ruby>失敗<rt>しっぱい</rt></ruby>しました。</p>
                    <?php endif ?>

                    <hr>

                    <div class="control_check">
                        <p class="moji-check"><ruby>家族名<rt>かぞくめい</rt></ruby></p>
                        <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['family_name'], ENT_QUOTES); ?></span></p>
                    </div>
        
                    <div class="control_check">
                        <p class="moji-check">ユーザーネーム</p>
                        <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['username'], ENT_QUOTES); ?></span></p>
                    </div>

                    <div class="control_check">
                        <p class="moji-check"><ruby>名字<rt>みょうじ</rt></ruby></p>
                        <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['last_name'], ENT_QUOTES); ?></span></p>
                    </div>

                    <div class="control_check">
                        <p class="moji-check"><ruby>名前<rt>なまえ</rt></ruby></p>
                        <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['first_name'], ENT_QUOTES); ?></span></p>
                    </div>

                    <div class="control_check">
                        <p class="moji-check"><ruby>生年月日<rt>せいねんがっぴ</rt></ruby></p>
                        <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['birthday'], ENT_QUOTES); ?></span></p>
                    </div>
                
                    <br>
                    
                    <a href="./entry.php" class="btn back-btn"><ruby>変更<rt>へんこう</rt></ruby>する</a>
                    <button type="submit" class="btn btn-primary"><ruby>登録<rt>とうろく</rt></ruby>する</button>
                    <div class="clear"></div>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- フッター -->
<?php require_once("../include/footer.php"); ?>