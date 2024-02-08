<!-- トップページ画面親用　テスト作成中 -->

<!-- ヘッダー -->
<?php
$page_title = "トップページ";
include("./include/header.php");
?>

<?php
// testpointクラスのインスタンスを作成
require($absolute_path."lib/testpoint_class.php");
$testpoint = new testpoint($db);
?>

<style>
    .action-btn {
        background-color: lemonchiffon;
        border-radius: 2rem;
        box-shadow: 0 6px 8px 0 rgba(0, 0, 0, .5);
        /* height: 30%; */
    }
</style>

<!-- ナビゲーションバー -->
<?php include_once("./include/nav_bar.php") ?>

<main>
    <!-- ロゴ -->
    <header class="position-relative h-25" style="padding-top: 4rem;">
        <img class="d-block mx-auto py-3" src="<?php echo $absolute_path; ?>static/assets/logo.png" height="120">
    </header>
    
    <section class="position-relative h-75">
        <div class="container px-4">
            <div class="row row-cols-1 row-cols-md-3 gx-3 gy-5 justify-content-around">
                <div class="col col-md-2">
                    <div class="row row-cols-2 row-cols-md-1 gy-4 justify-content-around">
                        <a class="col-5 col-md py-4 action-btn" href="./point/help_add.php">
                            <img class="d-block mx-auto" src="<?php echo $absolute_path; ?>static/assets/mission.png">
                        </a>
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
                        <a class="col-5 col-md py-4 action-btn" href="./record/calendar.php">
                            <img class="d-block mx-auto" src="<?php echo $absolute_path; ?>static/assets/Calendar.png" data-tab="5">
                        </a>
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

<!-- フッター -->
<?php include_once("./include/footer.php"); ?>