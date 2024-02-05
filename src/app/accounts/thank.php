<!-- 登録完了画面 -->

<!-- ヘッダー -->
<?php
$page_title = "ユーザー登録完了";
$stylesheet_name = "thank.css";
require_once("../include/header.php");
?>

<main>
    <div class="content">
        <div class="frame_check">
                <div class="wrapper1">
                    <img src="<?php echo $absolute_path; ?>static/assets/registration_completedC.png" height="100" class="registration_completedC">
            <h1>ユーザー登録が完了しました。</h1>
            <p>下のボタンよりログインページに移動してください。</p>
            <br>
            <p>
                <a href="./login.php"><button class="btn btn-primary">ログインページに移動する</button></a>
            </p>

            </div>
        </div>
    </div>
</main>

<!-- フッター -->
<?php require_once("../include/footer.php"); ?>