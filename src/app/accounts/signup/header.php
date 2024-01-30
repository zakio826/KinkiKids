<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="//fonts.googleapis.com">
    <link rel="preconnect" href="//fonts.gstatic.com" crossorigin>
    <link href="//fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.min.css">
    <link rel="preconnect" href="//fonts.googleapis.com">
    <link rel="preconnect" href="//fonts.gstatic.com" crossorigin>
    <link href="//fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&family=Zen+Maru+Gothic:wght@400;500;700;900&display=swap" rel="stylesheet">
    <script src="../js/footer-fixed.js"></script>
    <link rel="shortcut icon" href="../img/favicon.ico">
    <title>金記キッズ｜<?php echo $page_title; ?></title>
</head>

<?php
$time = new DateTime();
?>

<body class="body-<?php echo $time->format("H:i") < "19:00" ? "daytime" : "night"; ?>">
    <header class="l-header--join">
        <h1 class="l-header__title l-header__title--join">会員登録</h1>
    </header>