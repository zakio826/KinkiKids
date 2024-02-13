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

        <!-- Google Fonts -->
        <link rel="preconnect" href="//fonts.googleapis.com">
        <link rel="preconnect" href="//fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="//fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&display=swap">
        <link rel="stylesheet" href="//fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&family=Zen+Maru+Gothic:wght@400;500;700;900&display=swap">

        <!-- BootStrap5.3 CDN (css) -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <!-- BootStrap5.3 CDN (js) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>

        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script> -->


        <!-- カスタムスタイルシート -->
        <!-- <link rel="stylesheet" href="<?php echo $absolute_path; ?>static/css/style.css"> -->
        <?php if (isset($stylesheet_name)) : ?>
            <link rel="stylesheet" href="<?php echo $absolute_path; ?>static/css/<?php echo $stylesheet_name; ?>">
        <?php endif; ?>

        <!-- アプリアイコン -->
        <link rel="shortcut icon" href="<?php echo $absolute_path; ?>static/assets/favicon.ico">
        
        <!-- アプリタイトル（$page_titleにページ名を代入してからこのファイルを参照する） -->
        <title>金記キッズ｜<?php echo $page_title; ?></title>
    </head>

    <style>
        html {
            position: relative;
            min-height: 100vh;
        }
        main {
            position: relative;
            min-height: calc(100vh - 4rem);
            margin-bottom: 150px;
        }

        <?php if ($accounts_page) : $select = "child"; ?>
        <?php else : $select = $_SESSION["select"]; ?>
        <?php endif; ?>

        body {
            background: url('<?php echo $absolute_path . "static/assets/" . $select . "_back_image.png"; ?>');
            /* background: url('<?php echo $absolute_path; ?>static/assets/child_back_image.png'); */
        }
    </style>

    <body>