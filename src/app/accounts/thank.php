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
                
                <h1>ユーザー<ruby>登録<rt>とうろく</rt></ruby>が<ruby>完了<rt>かんりょう</rt></ruby>しました。</h1>
                <p class="mb-3"><ruby>下<rt>した</rt></ruby>のボタンよりログインページに<ruby>移動<rt>いどう</rt></ruby>してください。</p>

                <p><a href="./login.php"><button class="btn btn-primary">ログインページに<ruby>移動<rt>いどう</rt></ruby>する</button></a></p>
            </div>
        </div>
    </div>
</main>

<!-- フッター -->
<?php require_once("../include/footer.php"); ?>