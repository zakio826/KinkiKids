<?php
$page_title = "パスワード再設定完了";
$page_headertitle = "パスワード再設定";
include_once("./header.php");
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="//fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../css/style.min.css" />
    <script src="../js/footer-fixed.js"></script>
    <link rel="preconnect" href="//fonts.googleapis.com" />
    <link rel="preconnect" href="//fonts.gstatic.com" crossorigin />
    <link href="//fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&family=Zen+Maru+Gothic:wght@400;500;700;900&display=swap" rel="stylesheet" />
    <link rel="icon" href="../favicon.ico" />
    <title>金記キッズ｜パスワード再設定完了</title>
</head>

<body>
    <header class="l-header--join">
        <h1 class="l-header__title l-header__title--join">パスワード再設定</h1>
    </header>

    <main class="l-main">
        <section class="p-section p-section__reset-complete">
            <div class="p-message-box p-message-box--desc">
                <p>パスワードの再設定が完了しました</p>
                <a class="c-button c-button--bg-blue" href="../login.php">ログイン画面へ</a>
            </div>
        </section>
    </main>

    <?php
    $footer_back = "off";
    include_once("../component/common/footer.php");
    ?>
</body>

</html>