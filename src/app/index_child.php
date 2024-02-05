<!-- トップページ画面 -->

<!-- ヘッダー -->
<?php
$page_title = "トップページ";
include("./include/header.php");
?>

<?php
require("../../config/db_connect.php");
session_start();
$db = new connect();

// testpointクラスのインスタンスを作成
require("../../lib/testpoint_class.php");
$testpoint = new testpoint($db);

require("../../lib/index_child_class.php");
$index_child_class = new index_child_class($db);
$have_points = $index_child_class->getHave_points();
$savings = $index_child_class->getSavings();
$have_money = $have_points+$savings;
$goal_count = $index_child_class->getGoalCount();
$help_count = $index_child_class->getHelpCount();
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
    <?php include_once("./include/nav_bar.php") ?>

    <!-- ロゴ -->
    <header class="position-relative h-25" style="padding-top: 4rem;">
        <img class="d-block mx-auto py-3" src="<?php echo $absolute_path; ?>static/assets/logo.png" height="120">
    </header>
    
    <section class="position-relative h-75">
        <div class="container px-4">
            <div class="row row-cols-1 row-cols-md-3 gx-3 gy-5 justify-content-around">
                <div class="col col-md-2">
                    <div class="row row-cols-2 row-cols-md-1 gy-4 justify-content-around">
                        <div class="col-5 col-md py-4 action-btn">
                            <!-- <img class="d-block mx-auto" src="<?php echo $absolute_path; ?>static/assets/mission.png"> -->
                            <p><strong>現在のお金（ポイント＋貯金）:</strong> <?php echo htmlspecialchars($have_money); ?> 円</p>
                            <p>ポイント:<strong> <?php echo htmlspecialchars($have_points); ?></strong> ポイント</p>
                            <p>貯金:<strong> <?php echo htmlspecialchars($savings); ?></strong> 円</p>

                        </div>
                        <div class="col-5 col-md py-4 action-btn">
                        <p><strong>目標</strong></p>
                        <?php if($goal_count != 0){ ?>
                            <?php for($i=0;$i<$goal_count;$i++){ ?>
                                <p>内容:<strong><?php echo htmlspecialchars($index_child_class->getGoal_detail($i)); ?></strong> </p>
                                <p>いつまで:<strong><?php echo htmlspecialchars($index_child_class->getGoal_deadline($i)); ?></strong> </p>
                                <p>目標金額:<strong><?php echo htmlspecialchars($index_child_class->getTarget_amount($i)); ?></strong> 円</p>
                                <hr>
                            <?php } ?>
                        <?php } else { ?>
                                <p>目標を設定してください</p>
                        <?php } ?>

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
                        <p><strong>目標を達成するには</strong></p>
                        <?php if($goal_count != 0){ ?>
                            <?php for($i=0;$i<$goal_count;$i++){ ?>
                                <p>お小遣いが1ヶ月に1回もらえるのをふまえると合計であと<strong><?php echo htmlspecialchars($index_child_class->getRequired_point($i)); ?></strong> ポイント必要です</p>
                                <p>期限までに目標金額を達成するには１日あたりあと<strong><?php echo htmlspecialchars($index_child_class->getOnerequired_point($i)); ?></strong> ポイント必要です</p>
                                <hr>
                            <?php } ?>
                        <?php } else { ?>
                                <p>目標を設定してください</p>
                        <?php } ?>
                        </div>
                        <div class="col-5 col-md py-4 action-btn">
                        <p><strong>お手伝い</strong></p>
                        <?php if($help_count != 0){ ?>
                            <?php for($i=0;$i<$help_count;$i++){ ?>
                                <p>お手伝い:<strong><?php echo htmlspecialchars($index_child_class->getHelp($i)); ?></strong> </p>
                                <hr>
                            <?php } ?>
                        <?php } else { ?>
                                <p>お手伝いを設定してください</p>
                        <?php } ?>
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