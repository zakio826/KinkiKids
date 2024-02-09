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
?>


<!-- ナビゲーションバー -->
<?php include_once("./include/nav_bar.php") ?>

<main>
    <!-- ロゴ -->
    <header class="position-relative h-25" style="padding-top: 4rem;">
        <img class="d-block mx-auto py-3 index_child_logo" src="<?php echo $absolute_path; ?>static/assets/logo.png" height="120">
    </header>
    
    <section class="position-relative h-75">
        <div class="index_child_mokuhyoucss1">
            <div class="index_child_mokuhyoucss2">
            <?php if ($goal_count != 0) : ?>
                <a href="./goal/goal_detail.php">ちかぢかせまっているもくひょう<br>
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
            <p>
                しょじきん: <span><?php echo htmlspecialchars($savings); ?></span> えん　
                てもち: <span><?php echo htmlspecialchars($have_points); ?></span> ポイント
            </p>

            <?php if($goal_count != 0) : ?>
                <p>
                    <br>きょうかせぐポイント: 
                    <span>
                        <?php echo htmlspecialchars($index_child_class->getOnerequired_point()); ?>
                    </span> ポイント
                </p>
            <?php else : ?>
                <p class="index_child_moji">
                    <br>目標がないので設定してください
                </p>
            <?php endif; ?>
        </div>
        </div>
        <hr>

        <nav>
            <ul>
                <li><a href="<?php echo $absolute_path; ?>src/app/goal/goal.php"><img src="">購入目標</a></li>
                <li><a href="<?php echo $absolute_path; ?>src/app/point_norma/setting_norma.php"><img src="">ポイントノルマ</a></li>
            </ul>
        </nav>

        <hr>

        <p>メッセージ</p>
        <?php for($i=0;$i<$message_count;$i++){ ?>
            <?php
                echo htmlspecialchars(
                    $index_child_class->getMessage($i)['sender'].
                    '➡'.
                    $index_child_class->getMessage($i)['receiver'].
                    '：'.
                    $index_child_class->getMessage($i)['messagetext']
                );
                echo '<br>';
            ?>
        <?php } ?>
        <p>メッセージの絞り込みをする</p>
        <select id="user_select">
            <option value=""></option>
            <?php $index_child_class->getFamilyUser(); ?>
        </select>
        <p id="order-string"></p>


            </div>
        </div>
        <hr class="index_child_hr">
        <br>

        <!-- <hr class="index_child_hr"> -->
        <div class="index_child_messagecss1">
            <div class="index_child_messagecss2">
            <div class="index_child_messagecss3">

            <!-- <span>
                <p>メッセージ</p>
            </span> -->
            <p><img src="<?php echo $absolute_path; ?>static/assets/messageC.png" height=40></p>
            <select id="user_select">
                <option value=""></option>
                <?php $index_child_class->getFamilyUser(); ?>
            </select>

            <p id="order-string"></p><br>

            <?php if ($message_count != 0) : ?>
                <?php for ($i = 0; $i < $message_count; $i++) : ?>
                    <?php echo htmlspecialchars($index_child_class->getMessage($i)['sender']); ?>
                    ➡
                    <?php echo htmlspecialchars($index_child_class->getMessage($i)['receiver']); ?>
                    
                    <p><?php echo htmlspecialchars($index_child_class->getMessage($i)['messagetext']); ?> </p>
                    <hr>
                <?php endfor; ?>
            <?php else : ?>
                <p>メッセージがありません</p>
            <?php endif; ?>
            </div>
            </div>
        </div>
    </section>
    <!-- ナビゲーションバー -->
    <?php include_once("./include/bottom_nav.php") ?>
</main>

<script>
    let select = document.getElementById('user_select');
    let count = <?php echo $message_count; ?>;
    select.addEventListener('change', (e) => {
        let selected_value = document.getElementById('user_select').value;
        let message = [];
        <?php for($i=0;$i<$message_count;$i++){ ?>
        
            if((selected_value==<?php echo htmlspecialchars($index_child_class->getMessage($i)['receiver_id']); ?>) && (<?php echo htmlspecialchars($index_child_class->getMessage($i)['session_user']); ?> == <?php echo htmlspecialchars($index_child_class->getMessage($i)['sender_id']); ?>) || (selected_value==<?php echo htmlspecialchars($index_child_class->getMessage($i)['sender_id']); ?>) && (<?php echo htmlspecialchars($index_child_class->getMessage($i)['session_user']); ?> == <?php echo htmlspecialchars($index_child_class->getMessage($i)['receiver_id']); ?>)){
                if((selected_value==<?php echo htmlspecialchars($index_child_class->getMessage($i)['receiver_id']); ?>) && (<?php echo htmlspecialchars($index_child_class->getMessage($i)['session_user']); ?> == <?php echo htmlspecialchars($index_child_class->getMessage($i)['sender_id']); ?>)){
                    message.push('自分：'+'<?php echo htmlspecialchars($index_child_class->getMessage($i)['messagetext']); ?>');
                }else{
                    message.push('<?php echo htmlspecialchars($index_child_class->getMessage($i)['sender']); ?>'+'：'+'<?php echo htmlspecialchars($index_child_class->getMessage($i)['messagetext']); ?>');
                }
            }

        <?php } ?>
        let str = message.join('<br>');
        document.getElementById('order-string').innerHTML = str;
    });
</script>

<!-- フッター -->
<?php include_once("./include/footer.php"); ?>
