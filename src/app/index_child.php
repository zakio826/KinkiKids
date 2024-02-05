<!-- トップページ画面子用　テスト作成中 -->

<!-- ヘッダー -->
<?php
$page_title = "トップページ";
include("./include/header.php");
?>

<?php

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
    .modal-2__wrap input {
    display: none;
    }

    .modal-2__open-label,
    .modal-2__close-label {
        cursor: pointer;
    }
    .modal-2__open-label {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 250px;
        margin:0 auto;
        padding: .8em 2em;
        border: none;
        border-radius: 5px;
        background-color: #2589d0;
        color: #ffffff;
        font-weight: 600;
        font-size: 1em;
    }
    .modal-2__open-label:hover {
        background-color: #fff;
        color: #2589d0;
        outline: 1px solid #2589d0;
    }
    .modal-2 {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        display: none;
    }
    .modal-2__open-input:checked + label + input + .modal-2 {
        display: block;
        animation: modal-2-animation .6s;
    }
    .modal-2__content-wrap {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 80%;
        max-width: 650px;
        background-color: #fefefe;
        z-index: 2;
        border-radius: 5px;
    }
    .modal-2__close-label {
        background-color: #777;
        color: #fff;
        border: 2px solid #fff;
        border-radius: 20px;
        width: 36px;
        height: 36px;
        line-height: 1.6;
        text-align: center;
        display: table-cell;
        position: fixed;
        top: -15px;
        right: -2%;
        z-index: 99999;
        font-size: 1.3em;
    }
    .modal-2__content {
        max-height: 50vh;
        overflow-y: auto;
        padding: 39px 45px 40px;
    }
    .modal-2__background {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .45);
        z-index: 1;
    }
    @keyframes modal-2-animation {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }
    @media only screen and (max-width: 520px) {
        .modal-2__open-label {
            max-width: 90%;
            padding: .94em 2.1em .94em 2.6em;
        }
        .modal-2__close-label {
            top: -17px;
            right: -4%;
        }
        .modal-2__content-wrap {
            width: 90vw;
        }
        .modal-2__content {
            padding: 33px 21px 35px;
            max-width: 100%;
        }
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
        <?php if($goal_count != 0){ ?>
            <a href="./goal/goal_detail.php">
                もくひょう<br>
                <?php echo htmlspecialchars($index_child_class->getGoal_detail()); ?><br>
                <?php echo htmlspecialchars($index_child_class->getGoal_deadline()); ?> 
                <?php echo htmlspecialchars($index_child_class->getTarget_amount()); ?> 円
            </a>
        <?php } else { ?>
            <p>目標がないので設定してください</p>
        <?php } ?>
        <hr>
        ちょきん: <?php echo htmlspecialchars($savings); ?> えん　　
        てもち: <?php echo htmlspecialchars($have_points); ?> ポイント
        <p>ごうけい: <?php echo htmlspecialchars($have_money); ?> えん</p>
        <p>きょうかせぐポイント: <?php echo htmlspecialchars($index_child_class->getOnerequired_point()); ?> ポイント</p>
        <hr>

        <div class="modal-2__wrap"> 
            <input type="radio" id="modal-2__open" class="modal-2__open-input" name="modal-2__trigger"/>
            <label for="modal-2__open" class="modal-2__open-label">きょうのおてつだいをひょうじ</label>
            <input type="radio" id="modal-2__close" name="modal-2__trigger"/>
            <div class="modal-2">
                <div class="modal-2__content-wrap">
                    <label for="modal-2__close" class="modal-2__close-label">×</label>
                    <div class="modal-2__content">
                    <p>みっしょん</p>
                    <?php if($help_count != 0){ ?>
                        <?php for($i=0;$i<$help_count;$i++){ ?>
                            <p>・<?php echo htmlspecialchars($index_child_class->getHelp($i)); ?> </p>
                            <hr>
                        <?php } ?>
                    <?php } else { ?>
                            <p>お手伝いを設定してください</p>
                    <?php } ?>

                    </div>
                </div>
                <label for="modal-2__close">
                    <div class="modal-2__background"></div>
                </label>
            </div>
        </div>
    </section>
</main>

<!-- フッター -->
<?php include_once("./include/footer.php"); ?>