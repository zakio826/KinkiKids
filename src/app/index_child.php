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
        <img class="d-block mx-auto py-3" src="<?php echo $absolute_path; ?>static/assets/logo.png" height="120">
    </header>
    
    <section class="position-relative h-75">
        <div class="index_child_mokuhyoucss1">
            <div class="index_child_mokuhyoucss2">
            <?php if ($goal_count != 0) : ?>
                <a href="./goal/goal_detail.php">もくひょう<br>
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
            <p>
                しょじきん: <span><?php echo htmlspecialchars($savings); ?></span> えん　
                てもち: <span><?php echo htmlspecialchars($have_points); ?></span> ポイント
            </p>
            <p class="index_child_moji">
                <br>ごうけい: <span><?php echo htmlspecialchars($have_money); ?></span> えん
            </p>
            <?php if($goal_count != 0) : ?>
                <p>
                    <br>きょうかせぐポイント: 
                    <span>
                        <?php echo htmlspecialchars($index_child_class->getOnerequired_point()); ?>
                    </span> ポイント
                </p>
            <?php else : ?>
                <p>
                    <br>目標がないので設定してください
                </p>
            <?php endif; ?>
            </div>
        </div>
        <hr class="index_child_hr">

        <div class="modal-2__wrap"> 
            <input type="radio" id="modal-2__open" class="modal-2__open-input" name="modal-2__trigger"/>
                <label for="modal-2__open" class="modal-2__open-label">
                    <img src="<?php echo $absolute_path; ?>static/assets/otetudaiA.png" height=55>
                </label>
            <input type="radio" id="modal-2__close" name="modal-2__trigger"/>

            <div class="modal-2">
                <div class="modal-2__content-wrap">
                    <label for="modal-2__close" class="modal-2__close-label">×</label>
                    <div class="modal-2__content">
                        <span>
                            <p>みっしょん</p>
                        </span>

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
