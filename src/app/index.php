<?php
// ホームページ画面PHP

session_start();
// セッション変数 $_SESSION["loggedin"]を確認。未ログインだったらログインページへリダイレクト
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: ./accounts/login.php");
    exit;
}

require("../../config/db_connect.php");
require("../../lib/testpoint_class.php");
$db = new connect();
$testpoint = new testpoint($db);

$stmt = $db->prepare("SELECT * FROM user WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION["user_id"]);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$page_title = "ホームページ";
require_once("./include/header.php");
?>

<!--
<a href="./accounts/entry.php">新規登録</a>
<a href="./accounts/login.php">ログイン</a>
<a href="./goal/goal.php">目標</a>
<a href="./point/help_add.php">お手伝い</a>
-->


<!-- <main>
    <h1 class="my-5">Hi,<b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>

    <p>
        <a href="./logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
    </p>
    <a href="../point/help_add.php">お手伝い</a>
    <a href="../goal/goal.php">目標</a>
</main> -->

<style>
    .action-btn {
        background-color: lemonchiffon;
        border-radius: 2rem;
        box-shadow: 0 6px 8px 0 rgba(0, 0, 0, .5);
    }
</style>

<main class="position-relative m-0" style="margin-bottom: 120px;">
    <!-- ナビゲーションバー -->
    <nav class="position-absolute w-100" style="background-color: lemonchiffon;">
        <div class="container px-3 py-2">
            <div class="row align-items-center justify-content-between">
                <div class="col">
                    <?php if ($users[0]["role_id"] > 30) : ?>
                        <h3 class="d-inline">
                            おなまえ：<span class="px-2"><?php echo $users[0]["last_name"]." ".$users[0]["first_name"]; ?></span>さん
                        </h3>
                    <?php else : ?>
                        <h3 class="row row-cols-3 justify-content-start">
                            <span class="col-auto">ユーザー名：</span>
                            <span class="col-auto"><?php echo $users[0]["last_name"]." ".$users[0]["first_name"]; ?></span>
                            <span class="col-auto">さん</span>
                        </h3>
                    <?php endif; ?>
                </div>
                <div class="col-auto"><img src="<?php echo $absolute_path; ?>static/assets/Cog.png" width="40" height="40" data-tab="3"></div>
            </div>
        </div>
    </nav>

    <!-- ホーム画面 -->
    <header style="padding-top: 5rem;">
        <div class="container py-3">
            <img class="d-block mx-auto" src="<?php echo $absolute_path; ?>static/assets/logo.png" height="100">
        </div>
    </header>
    
    <section class="">
        <div class="container px-4">
            <div class="row row-cols-1 row-cols-md-3 gx-3 gy-5 justify-content-around">
                <div class="col col-md-2">
                    <div class="row row-cols-2 row-cols-md-1 gy-4 justify-content-around">
                        <div class="col-5 col-md py-4 action-btn">
                            <img src="<?php echo $absolute_path; ?>static/assets/mission.png">
                        </div>
                        <div class="col-5 col-md py-4 action-btn">
                            <img src="<?php echo $absolute_path; ?>static/assets/Coin.png">
                        </div>
                    </div>
                </div>

                <div class="col-10 col-sm-8 col-md-6 px-5 action-btn">
                    <div class="h-auto" style="max-height: 50%;">
                        <div class="my-3" style="height: 10rem;">
                            <?php $testpoint->role_select(); ?>
                        </div>
                    </div>
                </div>

                <div class="col- col-md-2">
                    <div class="row row-cols-2 row-cols-md-1 gy-4 justify-content-around">
                        <div class="col-5 col-md py-4 action-btn">
                            <img src="<?php echo $absolute_path; ?>static/assets/Calendar.png" data-tab="5">
                        </div>
                        <div class="col-5 col-md py-4 action-btn">
                            <img src="<?php echo $absolute_path; ?>static/assets/Cog.png" data-tab="1">
                        </div>
                    </div>
                </div>
                <!-- <img src="./img/household.png" id="householdBtn" data-tab="4"> -->
            </div>
        </div>
    </section>
</main>

<?php require_once("./include/footer.php"); ?>