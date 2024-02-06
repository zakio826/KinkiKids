<?php
// 動的にルートディレクトリまで繋げるパスを生成する
$url_path = explode("/", $_SERVER["REQUEST_URI"]);
$absolute_path = "../";
$accounts_page = false;
for ($i = 3; $i < count($url_path); $i++) {
    if ($url_path[$i] === "accounts") {
        $accounts_page = true;
    }
    $absolute_path .= "../";
}
// $absolute_pathの後に絶対パスを記述する
?>

<?php
// セッション開始
session_start();
if (!$accounts_page) {
    // セッション変数 $_SESSION["loggedin"]を確認。未ログインだったらログインページへリダイレクト
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("Location: ".$absolute_path."src/app/accounts/login.php"); exit;
    }
}
?>

<?php
// データベース接続
require($absolute_path."config/db_connect.php");
$db = new connect();
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="//fonts.googleapis.com">
        <link rel="preconnect" href="//fonts.gstatic.com" crossorigin>
        <link href="//fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal.min.css">
        <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal-default-theme.min.css">
        <link rel="stylesheet" type="text/css" href="../../../static/css/login.css">

        <link rel="preconnect" href="//fonts.googleapis.com">
        <link rel="preconnect" href="//fonts.gstatic.com" crossorigin>
        <link href="//fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&family=Zen+Maru+Gothic:wght@400;500;700;900&display=swap" rel="stylesheet">

        <!-- BootStrap5.3 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <!-- BootStrap5.3 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>

        <!-- カスタムスタイルシート -->
        <!-- <link rel="stylesheet" href="<?php echo $absolute_path; ?>static/css/style.css"> -->
        <link rel="stylesheet" href="<?php echo $absolute_path; ?>static/css/login.css">
        <link rel="stylesheet" href="<?php echo $absolute_path; ?>static/css/help_add.css">
        <link rel="stylesheet" href="<?php echo $absolute_path; ?>static/css/goal_check.css">
        <link rel="stylesheet" href="<?php echo $absolute_path; ?>static/css/list.css">

        <!-- アプリアイコン -->
        <link rel="shortcut icon" href="<?php echo $absolute_path; ?>static/assets/favicon.ico">
        
        <!-- アプリタイトル（$page_titleにページ名を代入してからこのファイルを参照する） -->
        <title>金記キッズ｜<?php echo $page_title; ?></title>
    </head>

    <style>
        html, body {
            position: relative;
            max-height: 100%;
            height: 100%;
        }
        main {
            position: relative;
            max-height: 100%;
            padding-bottom: 4rem;
        }
    </style>

    <body style="background:url('<?php echo $absolute_path; ?>static/assets/back_image.png');">
