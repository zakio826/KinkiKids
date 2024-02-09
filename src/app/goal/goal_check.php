<?php
$page_title = "目標";
$stylesheet_name = "goal_check.css";
require_once("../include/header.php");
?>

<?php
require($absolute_path."lib/goal_class.php");
$goal_check = new goal_check($db);
$goal_user_name = $goal_check->getusername($db); 

// if (!isset($_SESSION['join'])) { //
//     header('Location: ./goal.php');
//     exit();
// }

$targetAmount = $_SESSION['join']['target_amount'];
$goalDetail = $_SESSION['join']['goal_detail'];
$goalDeadline = $_SESSION['join']['goal_deadline'];

// unset($_SESSION['join']);
?>

<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <div class="container--">
        <h1>もくひょうかくにん</h1>

        <div class="mt-1">
            <strong>たんとう　</strong>
            <p><?php echo htmlspecialchars($goal_user_name); ?></p>
        </div>

        <div class="mt-1">
            <strong>きんがく　</strong>
            <p><?php echo htmlspecialchars($targetAmount); ?> 円</p>
        </div>

        <div class="mt-1">
            <strong>しょうさい　</strong>
            <p><?php echo htmlspecialchars($goalDetail); ?></p>
        </div>

        <div class="mt-1">
            <strong>きげん　　</strong>
            <p><?php echo htmlspecialchars($goalDeadline); ?></p>
        </div>

        <!-- <p class="mt-3 msg">以上の内容で登録しました</p> -->

        <p class="mt-2"><a href="goal_list.php" class="btn">目標リスト</a></p>
    </div>
</main>
<!-- ナビゲーションバー -->
<?php include_once("./include/bottom_nav.php") ?>
<!-- フッター -->
<?php require_once("../include/footer.php"); ?>
