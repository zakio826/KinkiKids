<!-- トップページ画面子用 -->

<!-- ヘッダー -->
<?php
$page_title = "子供用トップページ";
$stylesheet_name = "index_child.css";
include("./include/header.php");
?>

<?php

// testpointクラスのインスタンスを作成
require($absolute_path."lib/testpoint_class.php");
$testpoint = new testpoint($db);


require($absolute_path."lib/index_child_class.php");
$user_id = $_SESSION["user_id"];
$index_child_class = new index_child_class($db, $user_id);
$have_points = $index_child_class->getHave_points();
$savings = $index_child_class->getSavings();
$have_money = $have_points+$savings;
$goal_count = $index_child_class->getGoalCount();
$help_count = $index_child_class->getHelpCount();
$message_count = $index_child_class->getMessageCount();
$repayment = $index_child_class->display_consent_repayment($user_id);

if(isset($_SESSION['updated'])) {
    echo '<script>alert("借金の返済をしました");</script>' ;
    unset($_SESSION['updated']);
}



$index_child_class->message($db);
?>

<?php
    // セッション変数aが設定されていない場合は0で初期化
    if (!isset($_SESSION['goal_select'])) {
        $_SESSION['goal_select'] = 0;
    }
    // 右ボタンが押された場合
    if (isset($_POST['right'])) {
        $_SESSION['goal_select'] = ($_SESSION['goal_select'] + 1) % 3;
        
    }
    // 左ボタンが押された場合
    if (isset($_POST['left'])) {
        $_SESSION['goal_select'] = ($_SESSION['goal_select'] - 1 + 3) % 3; // マイナス値を防ぐために3を加える
    }
    // 現在の値を取得
    $goal_select = $_SESSION['goal_select'];
?>



<!-- ナビゲーションバー -->
<?php include_once("./include/nav_bar.php") ?>

<main>
    <!-- ロゴ -->
    <header class="position-relative h-25" style="padding-top: 4rem;">
        <img class="d-block mx-auto py-3 index_child_logo" src="<?php echo $absolute_path; ?>static/assets/logo.png" height="120">
    </header>
    
    <section class="position-relative h-75">
    <a href="<?php echo $absolute_path; ?>src/app/goal/goal_list.php" class="index_child_mokuhyouitiran">目標一覧</a>

    <?php 
    if (!empty($repayment)) {
        echo '<h2>借金返済</h2>';
        echo '<ul>';
        foreach ($repayment as $repayment_data) {
            echo '<li>';
            echo '<strong>内容:</strong> ' . $repayment_data['contents'] . '<br>';
            echo '<strong>借りた金額:</strong> ' . $repayment_data['debt_amount'] . '<br>';
            echo '<button><a href="./money/repayment.php?debt_id=' . $repayment_data['debt_id'] . '"/button>借金返済する</a>';
            echo '</li>';
        }
        echo '</ul>';
    }
    ?>



            <div class="index_child_mokuhyoucss1">
                <div class="index_child_mokuhyoucss2">
                    <?php if($_SESSION['goal_select'] == 0){ ?>
                        <?php if ($goal_count != 0) { ?>
                            <span>
                                <?php echo htmlspecialchars($index_child_class->getGoal_detail()); ?><br>
                                <?php echo htmlspecialchars($index_child_class->getGoal_deadline()); ?> 
                                <?php echo htmlspecialchars($index_child_class->getTarget_amount()); ?> 円
                                <br>
                                <div class="btn-p">
                                    <a href="<?php echo $absolute_path; ?>src/app/goal/goal.php">
                                        ＋  
                                    </a>
                                </div>
                            <span>
                        <?php } else { ?>
                            <span><p>目標がないので設定してください</p></span>
                        <?php } ?>

                    <?php } elseif($_SESSION['goal_select'] == 1){ ?>
                        <b class="index_parent_mokuhyoumoji">
                                <?php echo htmlspecialchars($index_child_class->getPointNorma()['point_norma_amount']); ?><br>
                                <?php echo htmlspecialchars($index_child_class->getPointNorma()['point_norma_deadline']); ?> 
                            <br>
                            <div class="btn-p">
                                <a href="<?php echo $absolute_path; ?>src/app/point_norma/setting_norma.php">
                                    ＋
                                </a>
                            </div>
                        </b>
                    <?php } elseif($_SESSION['goal_select'] == 2){ ?>
                        <b class="index_parent_mokuhyoumoji">
                            <?php echo htmlspecialchars($index_child_class->getBehavioral()['behavioral_goal']); ?><br>
                            <?php echo htmlspecialchars($index_child_class->getBehavioral()['reward_point']); ?> 
                            <?php echo htmlspecialchars($index_child_class->getBehavioral()['behavioral_goal_deadline']); ?> 円
                            <br>
                            <div class="btn-p">
                                <a href="<?php echo $absolute_path; ?>src/app/behavioral_goal/setting_behavioral.php">
                                    ＋
                                </a>
                            </div>
                        </b>
                    <?php } ?>

                </div>
            </div>
        <form action="" method="post">
            <button type="submit" name="left" class="btn-left"><</button>
            <?php if($_SESSION['goal_select'] == 0){ ?>
                <span><?php echo '購入目標'; ?></span>
            <?php } elseif($_SESSION['goal_select'] == 1){ ?>
                <span><?php echo 'ポイントノルマ'; ?></span>
            <?php } elseif($_SESSION['goal_select'] == 2){ ?>
                <span><?php echo '行動目標'; ?></span>
            <?php } ?>

            <button type="submit" name="right" class="btn-right">></button>
        </form>





        <hr class="index_child_hr">
        <div class="index_child_mokuhyoucss3">
            <div class="index_child_mokuhyoucss4">
                <p class="row">
                    <span>
                        <b class="index_child_mokuhyoumoji">
                            しょじきん:
                        <span>
                            <?php echo htmlspecialchars($savings); ?>
                        </span>えん
                        </b>
                    </span>
                    <span>
                        <b class="index_child_mokuhyoumoji">
                        てもち:
                        <span>
                            <?php echo htmlspecialchars($have_points); ?>
                        </span>ポイント
                        </b>
                    </span>
                </p>
            <?php if($goal_count != 0) : ?>
                <p>
                    <span>
                        <b class="index_child_mokuhyoumoji">
                        きょうかせぐポイント:
                        <span>
                            <?php echo htmlspecialchars($index_child_class->getOnerequired_point()); ?>
                        </span>ポイント
                        </b>
                </p>
            <?php else : ?>
                <p class="index_child_moji">
                    <br>目標がないので設定してください
                </p>
            <?php endif; ?>
            </div>
        </div>

        <hr class="index_child_hr">

                <input type="radio" name="slideshow" id="slide1" checked>
                <input type="radio" name="slideshow" id="slide2">
                <input type="radio" name="slideshow" id="slide3">
            <div class="slideshow">
                <div class="slides">
                    <a href="<?php echo $absolute_path; ?>src/app/goal/goal.php">
                        <img class="slide slide1" src="<?php echo $absolute_path; ?>static/assets/mokuhyouA.png" alt="購入目標">  
                    </a>
                    <a href="<?php echo $absolute_path; ?>src/app/point_norma/setting_norma.php">
                        <img class="slide slide2" src="<?php echo $absolute_path; ?>static/assets/mokuhyouB.png" alt="ポイントノルマ">
                    </a>
                    <a href="<?php echo $absolute_path; ?>src/app/behavioral_goal/setting_behavioral.php">
                        <img class="slide slide3" src="<?php echo $absolute_path; ?>static/assets/mokuhyouC.png" alt="行動目標">
                    </a>
                </div>
            </div>
            <div class="slideshow-nav">
                <label for="slide1" class="slides-nav"></label>
                <label for="slide2" class="slides-nav"></label>
                <label for="slide3" class="slides-nav"></label>
            </div>


        
        <hr class="index_child_hr">
        <div class="index_child_mokuhyoucss1">
        <div class="index_child_mokuhyoucss2">

        <a href="<?php echo $absolute_path; ?>src/app/point/mission_add.php">
                <img src="<?php echo $absolute_path; ?>static/assets/kinkyuumi.png" height="50">
                
        </a>
            </div>
        </div>
        <br>

        <!-- <hr class="index_child_hr"> -->
        <div class="index_child_messagecss1">
            <div class="index_child_messagecss2">
            <div class="index_child_messagecss3">

            <!-- <span>
                <p>メッセージ</p>
            </span> -->
            <p>
                <img src="<?php echo $absolute_path; ?>static/assets/messageC.png" height=40 alt="メッセージ" class="index_child_message">
                <select id="user_select">
                        <option value=""></option>
                        <?php $index_child_class->getFamilyUser(); ?>
                </select>
            </p>
                <div class="login_scroll_bar">


           
                <div style="width: 100%; height: 100px; overflow-y: scroll; border: 1px #999999 solid;">
                <p class="mb-3" id="order-string"></p>
                </div> 

            <div style="width: 100%; height: 100px; overflow-y: scroll; border: 1px #999999 solid;">
                <?php if ($message_count != 0) : ?>
                    <?php for ($i = 0; $i < $message_count; $i++) : ?>
                        <?php echo htmlspecialchars($index_child_class->getMessage($i)['sender']); ?>
                        ➡
                        <?php echo htmlspecialchars($index_child_class->getMessage($i)['receiver']); ?>
                        
                        <p>
                        <?php echo htmlspecialchars($index_child_class->getMessage($i)['messagetext']); ?> 
                        <?php echo htmlspecialchars($index_child_class->getMessage($i)['sent_time']); ?> 
                        </p>

                        <hr>

                    <?php endfor; ?>
                <?php else : ?>
                    <p>メッセージがありません</p>
                <?php endif; ?>
            </div>

            <form action="" method="POST">
            <input type="hidden" name="check" value="checked">
                <p class="index_child_send">▼ メッセージ送信 ▼</p>
                <select name="receiver" required>
                    <option value=""></option>
                    <?php $index_child_class->getFamilyUser(); ?>
                </select>
                <input type="text" name="message" required>
                <button type="submit" class="btn">返信</button>
            </form>
                
            </div>
            </div>
        </div>
    </section>
</main>

<!-- ナビゲーションバー -->
<?php include_once("./include/bottom_nav.php") ?>

<script>    
    let select = document.getElementById('user_select');
    let count = <?php echo $message_count; ?>;

    select.addEventListener('change', (e) => {
        let selected_value = document.getElementById('user_select').value;
        let message = [];

        let xxx1 = null;
        let xxx2 = null;
        let xxx3 = null;
        let xxx4 = null;
        let xxx5 = null;
        
        <?php for ($i = 0; $i < $message_count; $i++) : ?>
            xxx1 = <?php echo htmlspecialchars($index_child_class->getMessage($i)['receiver_id']); ?>;
            xxx2 = <?php echo htmlspecialchars($index_child_class->getMessage($i)['session_user']); ?>;
            xxx3 = <?php echo htmlspecialchars($index_child_class->getMessage($i)['sender_id']); ?>;
            xxx4 = '<?php echo htmlspecialchars($index_child_class->getMessage($i)['messagetext']); ?>';
            xxx5 = '<?php echo htmlspecialchars($index_child_class->getMessage($i)['sender']); ?>';

            if (selected_value == xxx1 && xxx2 == xxx3 || selected_value == xxx3 && xxx2 == xxx1) {
                if (selected_value == xxx1 && xxx2 == xxx3) message.push('自分：' + xxx4);
                else message.push(xxx5 + '：' + xxx4);
            }
        <?php endfor; ?>

        let str = message.join('<br>');
        document.getElementById('order-string').innerHTML = str;
    });
</script>

<!-- フッター -->
<?php include_once("./include/footer.php"); ?>
