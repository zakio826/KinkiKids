<!-- サインアップ入力情報確認画面 -->

<!-- ヘッダー -->
<?php
$page_title = "入力情報確認";
require_once("../include/header.php");
?>

<?php
require($absolute_path."lib/check_class.php");
checkUser($db, $_SESSION['join']);
?>

<main>
    <div class="content">
        <form action="" method="POST">
            <input type="hidden" name="check" value="checked">
            <h1>入力情報の確認</h1>
            <p>ご入力情報に変更が必要な場合、下のボタンを押し、変更を行ってください。</p>
            <p>登録情報はあとから変更することもできます。</p>
            <?php if (!empty($error) && $error === "error"): ?>
                <p class="error">＊会員登録に失敗しました。</p>
            <?php endif ?>
            <hr>
 
            <div class="control">
                <p>ユーザーネーム</p>
                <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['username'], ENT_QUOTES); ?></span></p>
            </div>

            <div class="control">
                <p>名字</p>
                <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['last_name'], ENT_QUOTES); ?></span></p>
            </div>

            <div class="control">
                <p>名前</p>
                <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['first_name'], ENT_QUOTES); ?></span></p>
            </div>

            <div class="control">
                <p>生年月日</p>
                <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['birthday'], ENT_QUOTES); ?></span></p>
            </div>

            <div class="control">
                <p>手持ち金額</p>
                <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['savings'], ENT_QUOTES); ?></span></p>
            </div>

            <br>
            <a href="./entry.php" class="back-btn">変更する</a>
            <button type="submit" class="btn next-btn">登録する</button>
            <div class="clear"></div>
        </form>
    </div>
</main>

<!-- フッター -->
<?php require_once("../include/footer.php"); ?>