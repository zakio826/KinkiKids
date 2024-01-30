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

    <link rel="stylesheet" href="./css/style.min.css">
    <link rel="stylesheet" type="text/scss" href="./css/style.scss">
    <link rel="preconnect" href="//fonts.googleapis.com">
    <link rel="preconnect" href="//fonts.gstatic.com" crossorigin>
    <link href="//fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&family=Zen+Maru+Gothic:wght@400;500;700;900&display=swap" rel="stylesheet">
    <script src="./js/footer-fixed.js"></script>
    <link rel="shortcut icon" href="./img/favicon.ico">
    <title>金記キッズ｜<?php echo $page_title; ?></title>
</head>

<?php
$time = new DateTime();
?>

<body class="body-<?php echo ($time->format("H:i") < "19:00" && "06:00" < $time->format("H:i")) ? "daytime" : "night"; ?>" id="body">
    <?php
    $review_target = null;
    if ($select === "adult" && $_SERVER['REQUEST_URI'] === "/KinkiKids/index.php") {
        for ($i = 0; $i < count($user["child"]); $i++) {
            $review_date = new DateTime($user["child"][$i]["review_date"]);
            if ($user["child"][$i]["flag"] === 0 && $user["child"][$i]["review_date"] !== "" && $review_date->format("d") <= $today->format("d")) {
                if ($i === 0 || !isset($review_target)) {
                    $review_target .= $user["child"][$i]["name"] . "さん";
                } else {
                    $review_target .= "・" . $user["child"][$i]["name"] . "さん";
                }
            }
        }
    }
    ?>

    <?php if ($select === "adult" && isset($review_target)) : ?>
        <div class="announce">
            <p><?php echo $review_target; ?>は振り返りをしてください。</p>
            <a class="review_link" href="review_sheet.php">振り返りはこちら</a>
        </div>
    <?php endif ?>

    <!-- <img class="bgImg" src="./img/background.png"> -->
    <header class="l-header">
        <h1 class="l-header__title" id="not_kaigyo"><a href="./index.php">金記キッズ</a></h1>

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
        <div class="l-header__icon" id="icon_kotei">
            <a class="home" data-tab="0" href="./index.php">
                <!-- <img src="./img/house.png" class="icon_hiritu1"> -->
            <!-- <i class="fa-solid fa-house"></i> -->
            </a>
        <!-- <a href="./account.php">
            <i class="fa-solid fa-user"></i>
        </a> -->
            <span>
                <?php
                // 子供ユーザーの場合
                if ($select === "child") {
                    $cols = array(
                        "number",
                        "id",
                        "date",
                        "mission",
                        "flag",
                        "point",
                    );
                    $wheres = array(
                        "id" => ["=", "i", $user["parent"]],
                        "flag" => ["=", "i", 0],
                        "date" => ["=", "s", $time->format("Y-m-d")],
                    );
                    $order = array(
                        // "order" => ["id", true],
                    );
                    $result = select($line, $cols, "EmergencyMission", wheres: $wheres, group_order: $order);
                }
                ?>
                
                <?php if ($select == "child" && count($result) > 0 && $result[0]["flag"] == 0) : ?>
                    <img src="./img/mission_emergent.png" id="missionBtn" data-tab="5">
                <?php else : ?>
                    <!-- <img src="./img/mission.png" id="missionBtn" data-tab="5"> -->
                <?php endif; ?>
                <!-- <p><b>お手伝い</b></p> -->
                <!-- <img src="./img/household.png" id="householdBtn" data-tab="5"> -->
            </span>
        <!-- <a href="./logout.php" id="logoutButton" onclick="logoutConfirm();">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </a> -->
            <style>
                #not_kaigyo {
                    white-space: pre;
                }
                #icon_kotei {
                    position: fixed;
                    right: 10px;
                    margin-top: 20px;
                }
                .icon_hiritu1 {
                    margin-bottom: 5px;
                }
            </style>
        
        </div>
    </header>
    <!-- <body>
        <header class="l-header--join">
            <h1 class="l-header__title l-header__title--join">銀行</h1>
        </header> -->