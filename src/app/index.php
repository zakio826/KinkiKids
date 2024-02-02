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

<style>
    .action-btn {
        background-color: lemonchiffon;
        border-radius: 2rem;
        box-shadow: 0 6px 8px 0 rgba(0, 0, 0, .5);
        /* height: 30%; */
    }
</style>

<main>
    <!-- ナビゲーションバー -->
    <nav class="position-absolute w-100" style="height: 4rem; background-color: lemonchiffon;">
        <div class="container h-100 px-4">
            <div class="row align-items-center justify-content-between h-100">
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
    <header class="position-relative h-25" style="padding-top: 4rem;">
        <img class="d-block mx-auto py-3" src="<?php echo $absolute_path; ?>static/assets/logo.png" height="120">
    </header>
    
    <section class="position-relative h-75">
        <div class="container px-4">
            <div class="row row-cols-1 row-cols-md-3 gx-3 gy-5 justify-content-around">
                <div class="col col-md-2">
                    <div class="row row-cols-2 row-cols-md-1 gy-4 justify-content-around">
                        <div class="col-5 col-md py-4 action-btn">
                            <img class="d-block mx-auto" src="<?php echo $absolute_path; ?>static/assets/mission.png">
                        </div>
                        <div class="col-5 col-md py-4 action-btn">
                            <img class="d-block mx-auto" src="<?php echo $absolute_path; ?>static/assets/Coin.png">
                        </div>
                    </div>
                </div>

                <div class="col-10 col-sm-8 col-md-6 px-5 action-btn">
                    <div class="position-relative my-3" style="height: 8rem;">
                        <?php $testpoint->role_select(); ?>
                    </div>
                </div>

                <div class="col- col-md-2">
                    <div class="row row-cols-2 row-cols-md-1 gy-4 justify-content-around">
                        <div class="col-5 col-md py-4 action-btn">
                            <img class="d-block mx-auto" src="<?php echo $absolute_path; ?>static/assets/Calendar.png" data-tab="5">
                        </div>
                        <div class="col-5 col-md py-4 action-btn">
                            <img class="d-block mx-auto" src="<?php echo $absolute_path; ?>static/assets/Cog.png" data-tab="1">
                        </div>
                    </div>
                </div>
                <!-- <img src="./img/household.png" id="householdBtn" data-tab="4"> -->
            </div>
        </div>
    </section>
</main>

<?php require_once("./include/footer.php"); ?>