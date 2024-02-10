<!-- トップページ画面親用 -->

<!-- ヘッダー -->
<?php
$page_title = "大人用トップページ";
$stylesheet_name = "index_parent.css";
include("./include/header.php");
?>

<?php
// testpointクラスのインスタンスを作成
require($absolute_path."lib/testpoint_class.php");
$testpoint = new testpoint($db);

require($absolute_path."lib/index_parent_class.php");
$index_parent_class = new index_parent_class($db);

$message_count = $index_parent_class->getMessageCount();

$index_parent_class->message($db);

//family_addでのsessionがあれば完了の通知出す
if (isset($_SESSION['family_success']) && $_SESSION['family_success']) {
    echo '<script>alert("' . $_SESSION['family_count'] . '人の登録が完了しました。");</script>';
    unset($_SESSION['family_success'], $_SESSION['family_count']);
}

echo '<script>';
foreach ($index_parent_class->getFamily() as $parent) {
    $goal_deadline = $parent['goal_deadline'];
    if ($index_parent_class->isDeadlinePassed($goal_deadline)) {
        echo 'alert("子供の目標の期限が過ぎています！");';
        echo 'window.location.href = "./goal/again_goal.php";'; 
        break;
    }
}
$point_norma_deadline_passed = $index_parent_class->checkPointNormaDeadlinePassed();
if ($point_norma_deadline_passed) {
    echo 'alert("ポイントノルマの期限が過ぎています！");';
    echo 'window.location.href = "./point_norma/norma_again.php";';
}
$behavioral_goal_deadline_passed = $index_parent_class->behavioralNormaDeadlinePassed();
if ($behavioral_goal_deadline_passed) {
    echo 'alert("行動目標の期限が過ぎています！");';
    echo 'window.location.href = "./behavioral_goal/behavioral_again.php";';
}
echo '</script>';

?>
<ul>
    <li><a href="<?php echo $absolute_path; ?>src/app/goal/goal.php"><img src="">購入目標</a></li>
    <li><a href="<?php echo $absolute_path; ?>src/app/point_norma/setting_norma.php"><img src="">ポイントノルマ</a></li>
    <li><a href="<?php echo $absolute_path; ?>src/app/behavioral_goal/setting_behavioral.php"><img src="">行動目標</a></li>
</ul>


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
                    </div>
                </div>
            </div>
        </div>

        <select id="user">
            <option value=""></option>
            <?php $index_parent_class->getFamilyUser(); ?>
        </select>
        <br>
        目標：<p id="goal_detail"></p>
        期限：<p id="goal_deadline"></p>
        値段：<p id="target_amount"></p>
        <hr>
        貯金：<p id="savings"></p>
        手持ち：<p id="points"></p>
        合計：<p id="have"></p>
        今日稼ぐポイント：<p id="dayPoint"></p>
        <hr>

        <!-- <hr class="index_child_hr"> -->
        <div class="index_child_messagecss1">
            <div class="index_child_messagecss2">
                <div class="index_child_messagecss3">

                    <!-- <span>
                        <p>メッセージ</p>
                    </span> -->
                    <p>
                        <img src="<?php echo $absolute_path; ?>static/assets/messageC.png" height=40 alt="メッセージ">
                    </p>
                    <form action="" method="POST">
                        <input type="hidden" name="check" value="checked">
                        <p>誰に送るか</p>
                        <select name="receiver" required>
                            <option value=""></option>
                            <?php $index_parent_class->getFamilyUser(); ?>
                        </select>
                        <input type="text" name="message" required>
                        <button type="submit">返信</button>
                    </form>

                    <select id="user_select">
                        <option value=""></option>
                        <?php $index_parent_class->getFamilyUser(); ?>
                    </select>

           
                    <div style="width: 100%; height: 100px; overflow-y: scroll; border: 1px #999999 solid;">
                        <p class="mb-3" id="order-string"></p>
                    </div> 

                    <div style="width: 100%; height: 100px; overflow-y: scroll; border: 1px #999999 solid;">
                        <?php if ($message_count != 0) : ?>
                            <?php for ($i = 0; $i < $message_count; $i++) : ?>
                                <?php echo htmlspecialchars($index_parent_class->getMessage($i)['sender']); ?>
                                ➡
                                <?php echo htmlspecialchars($index_parent_class->getMessage($i)['receiver']); ?>
                                
                                <p>
                                    <?php echo htmlspecialchars($index_parent_class->getMessage($i)['messagetext']); ?> 
                                    <?php echo htmlspecialchars($index_parent_class->getMessage($i)['sent_time']); ?> 
                                </p>

                                <hr>

                            <?php endfor; ?>
                        <?php else : ?>
                            <p>メッセージがありません</p>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </section>
</main>

<script>
    let select = document.getElementById('user');
    let select1 = document.getElementById('user_select');
    let count = <?php echo $message_count; ?>;
    let goal_detail = '';
    let goal_deadline = '';
    let target_amount = '';
    let savings = '';
    let points = '';
    let have;
    let day;
    let dayPoint;
    let allowance_amount;
    select.addEventListener('change', (e) => {
        let selected_value = document.getElementById('user').value;
        <?php for($i=0;$i<count($index_parent_class->getFamily());$i++){ ?>
            if(selected_value == <?php echo $index_parent_class->getFamily()[$i]['user_id'] ?>){
                <?php 
                    $today = new DateTime('now');
                    $deadline = new DateTime($index_parent_class->getFamily()[$i]['goal_deadline']);
                ?>
                <?php if($today->format('Y-m-d') <= $deadline->format('Y-m-d')){ ?>
                     goal_detail = '<?php echo $index_parent_class->getFamily()[$i]['goal_detail'];?>';
                     goal_deadline = '<?php echo $index_parent_class->getFamily()[$i]['goal_deadline'];?>';
                     target_amount = '<?php echo $index_parent_class->getFamily()[$i]['target_amount'];?>';
                     savings = <?php echo $index_parent_class->getChildSavings($index_parent_class->getFamily()[$i]['user_id'])['savings'];?>;
                     points = <?php echo $index_parent_class->getChildSavings($index_parent_class->getFamily()[$i]['user_id'])['have_points'];?>;
                     have = savings + points;
                     allowance_amount = <?php echo $index_parent_class->getChildAllowance($index_parent_class->getFamily()[$i]['user_id'])['allowance_amount']; ?>;
                     day = <?php echo $today->diff($deadline)->format('%a'); ?>;
                     if(target_amount - have - allowance_amount * <?php echo date_diff($today, $deadline)->m; ?> >= 0){
                         if(day != 0){
                             dayPoint = (target_amount - have - allowance_amount * <?php echo date_diff($today, $deadline)->m; ?>) / day;
                        } else {
                            dayPoint = target_amount - have - allowance_amount * <?php echo date_diff($today, $deadline)->m; ?>;
                        }
                    } else {
                        dayPoint = 0;
                    }

                <?php } ?>
            }
        <?php } ?>
        document.getElementById('goal_detail').innerHTML = goal_detail;
        document.getElementById('goal_deadline').innerHTML = goal_deadline;
        document.getElementById('target_amount').innerHTML = target_amount;
        document.getElementById('savings').innerHTML = savings;
        document.getElementById('points').innerHTML = points;
        document.getElementById('have').innerHTML = have;
        document.getElementById('dayPoint').innerHTML = dayPoint;
    });




    select1.addEventListener('change', (e) => {
        let selected_value = document.getElementById('user_select').value;
        let message = [];

        let xxx1 = null;
        let xxx2 = null;
        let xxx3 = null;
        let xxx4 = null;
        let xxx5 = null;
        
        <?php for ($i = 0; $i < $message_count; $i++) : ?>
            xxx1 = <?php echo htmlspecialchars($index_parent_class->getMessage($i)['receiver_id']); ?>;
            xxx2 = <?php echo htmlspecialchars($index_parent_class->getMessage($i)['session_user']); ?>;
            xxx3 = <?php echo htmlspecialchars($index_parent_class->getMessage($i)['sender_id']); ?>;
            xxx4 = '<?php echo htmlspecialchars($index_parent_class->getMessage($i)['messagetext']); ?>';
            xxx5 = '<?php echo htmlspecialchars($index_parent_class->getMessage($i)['sender']); ?>';

            if (selected_value == xxx1 && xxx2 == xxx3 || selected_value == xxx3 && xxx2 == xxx1) {
                if (selected_value == xxx1 && xxx2 == xxx3) message.push('自分：' + xxx4);
                else message.push(xxx5 + '：' + xxx4);
            }
        <?php endfor; ?>

        let str = message.join('<br>');
        document.getElementById('order-string').innerHTML = str;
    });

</script>
<!-- ナビゲーションバー -->
<?php include_once("./include/bottom_nav.php") ?>
<!-- フッター -->
<?php include_once("./include/footer.php"); ?>