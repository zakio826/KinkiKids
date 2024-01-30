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

<body class="body-<?php echo ($time->format("H:i") < "19:00" && $time->format("H:i") < "6:00") ? "daytime" : "night"; ?>" id="body">
  <header class="l-header">
    <h1 class="l-header__title"><a href="../index.php">金記キッズ</a></h1>
    <!-- <div class="l-header_menu">
      <div class="p-hamburger-button" onclick="onToggleNavigation();" id="hamburgerButton">
        <i class="fa-solid fa-list-ul"></i>
        <i class="fa-solid fa-xmark"></i>
      </div>
      <div class="c-layer"></div>

      <ul class="p-navigation" id="navigation">
        <li>
          <a href="./index.php">
            <i class="fa-solid fa-house"></i>ホーム画面
          </a>
        </li>
        <li>
          <a href="./account.php">
            <i class="fa-solid fa-user"></i>ユーザー情報</a>
        </li>
        <li>
          <a href="./item-edit.php?editItem=1">
            <i class="fa-solid fa-pen"></i>選択項目の編集</a>
        </li>
        <li>
          <a href="./item-report.php"><i class="fa-solid fa-chart-simple"></i>項目別レポート</a>
        </li>
        <li>
          <a href="./amount-report.php"><i class="fa-solid fa-chart-simple"></i>年間収支レポート</a>
        </li>
        <li>
          <a href="./logout.php" id="logoutButton" onclick="logoutConfirm();">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>ログアウト
          </a>
        </li>
      </ul>
    </div> -->
    <div class="l-header__icon">
      <a href="../index.php">
        <i class="fa-solid fa-house"></i>
      </a>
      <a href="../account.php">
        <i class="fa-solid fa-user"></i>
      </a>
      <a href="../logout.php" id="logoutButton" onclick="logoutConfirm();">
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
      </a>

    </div>
  </header>
  <!-- <body>
    <header class="l-header--join">
        <h1 class="l-header__title l-header__title--join">銀行</h1>
    </header> -->