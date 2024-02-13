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
$index_child_class = new index_child_class($db);
$have_points = $index_child_class->getHave_points();
$savings = $index_child_class->getSavings();
$have_money = $have_points+$savings;
$goal_count = $index_child_class->getGoalCount();
$help_count = $index_child_class->getHelpCount();
$message_count = $index_child_class->getMessageCount();

$index_child_class->message($db);
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

        <div class="index_child_mokuhyoucss1">
            <div class="index_child_mokuhyoucss2">
            <?php if ($goal_count != 0) : ?>
                <a href="./goal/goal_detail.php">ちかぢかせまっているもくひょう<br>
                <span>
                    <?php echo htmlspecialchars($index_child_class->getGoal_detail()); ?><br>
                    <?php echo htmlspecialchars($index_child_class->getGoal_deadline()); ?> 
                    <?php echo htmlspecialchars($index_child_class->getTarget_amount()); ?> 円
                <span>
                </a>
            <?php else : ?>
                <span><p>目標がないので設定してください</p></span>
            <?php endif; ?>
            </div>
        </div>
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
