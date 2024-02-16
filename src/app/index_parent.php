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

$family_id = $_SESSION['family_id'];

echo '<script>';
$again_goal_passed = $index_parent_class->againgoalPassed($family_id);
if ($again_goal_passed) {
    echo 'alert("子供の目標の期限が過ぎています！");';
    echo 'window.location.href = "./goal/again_goal.php";';  
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



<!-- ナビゲーションバー -->
<?php include_once("./include/nav_bar.php") ?>

<main>
    <!-- ロゴ -->
    <header class="position-relative h-25" style="padding-top: 4rem;">
        <img class="d-block mx-auto py-3 index_parent_logo" src="<?php echo $absolute_path; ?>static/assets/logo.png" height="120">
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

        <div class="select_user">
        <select id="user">
            <option value=""></option>
            <?php $index_parent_class->getFamilyUser(); ?>
        </select>
            <p>の目標</p>
        </div>




        <hr class="index_parent_hr">



        <?php if($_SESSION['goal_select'] == 0){ ?>
            <div class="index_parent_mokuhyoucss1">
                <div class="index_parent_mokuhyoucss2">
                    <br>
                    <b class="index_parent_mokuhyoumoji">
                        購入目標：<p id="goal_detail"></p>
                        期限：<p id="goal_deadline"></p>
                        値段：<p id="target_amount"></p>
                        <div class="btn-p">
                            <a href="<?php echo $absolute_path; ?>src/app/goal/goal.php">
                                ＋  
                            </a>
                        </div>
                    </b>
                </div>
            </div>
        <?php } elseif($_SESSION['goal_select'] == 1){ ?>
            <div class="index_parent_mokuhyoucss1">
                <div class="index_parent_mokuhyoucss2">
                    <b class="index_parent_mokuhyoumoji">
                        ポイントノルマ：<p id="norma"></p>
                        期限：<p id="norma_deadline"></p>
                        <div class="btn-p">
                            <a href="<?php echo $absolute_path; ?>src/app/point_norma/setting_norma.php" class="btn-p">
                                ＋
                            </a>
                        </div>
                    </b>
                </div>
            </div>
        <?php } elseif($_SESSION['goal_select'] == 2){ ?>
            <div class="index_parent_mokuhyoucss1">
                <div class="index_parent_mokuhyoucss2">
                    <b class="index_parent_mokuhyoumoji">
                        行動目標：<p id="behavioral_goal"></p>
                        期限：<p id="behavioral_goal_deadline"></p>
                        報酬ポイント：<p id="reward_point"></p>
                        <!-- 行動目標に飛ぶボタン -->
                        <div class="btn-p">
                            <a href="<?php echo $absolute_path; ?>src/app/behavioral_goal/setting_behavioral.php">
                                ＋
                            </a>
                        </div>
                    </b>
                </div>
            </div>
        <?php } ?>
        <div class="select_user">
            <form action="" method="post"  class="slideshow">
                <button type="submit" name="left" class="btn-left"><</button>
                <?php if($_SESSION['goal_select'] == 0){ ?>
                    <span>
                        <img src="<?php echo $absolute_path; ?>static/assets/mokuhyouD2.png" height=50 alt="購入目標">
                        <?php echo ''; ?>
                    </span>
                <?php } elseif($_SESSION['goal_select'] == 1){ ?>
                    <span>
                        <img src="<?php echo $absolute_path; ?>static/assets/mokuhyouE2.png" height=50 alt="ポイントノルマ">
                        <?php echo ''; ?>
                    </span>
                <?php } elseif($_SESSION['goal_select'] == 2){ ?>
                    <span>
                        <img src="<?php echo $absolute_path; ?>static/assets/mokuhyouF2.png" height=50 alt="行動目標">
                        <?php echo ''; ?>
                    </span>
                <?php } ?>

                <button type="submit" name="right" class="btn-right">></button>
            </form>
        </div>


        <hr class="index_parent_hr">

        <div class="index_parent_mokuhyoucss1">
            <div class="index_parent_mokuhyoucss2">
                <b class="index_parent_mokuhyoumoji">
                    貯金：<p id="savings"></p>
                    手持ち：
                    <p id="points" class="btn-kankin_iti">
                        <a href="<?php echo $absolute_path; ?>src/app/money/exchange.php" class="btn-kankin">
                            換金
                        </a>
                    </p>
                    今日稼ぐポイント：<p id="dayPoint"></p>
                </b>
            </div>
        </div>



        <input type="radio" name="slideshow" id="slide1" checked>
                <input type="radio" name="slideshow" id="slide2">
                <input type="radio" name="slideshow" id="slide3">

        <hr class="index_parent_hr">

        <!-- <hr class="index_parent_hr"> -->
        <div class="index_parent_mokuhyoucss1">
            <div class="index_parent_mokuhyoucss2">
        <!-- <div class="index_parent_kinkyuu"> -->
            <a href="<?php echo $absolute_path; ?>src/app/point/mission_add.php">
                <img src="<?php echo $absolute_path; ?>static/assets/kinkyuumi.png" height="50">
                
            </a>
            <a href="<?php echo $absolute_path; ?>src/app/point/consent.php">
                <img src="<?php echo $absolute_path; ?>static/assets/syouninnmati.png" height="50">
                
            </a>
            </div>
        </div>

        <!-- <hr class="index_parent_hr"> -->
        <div class="index_parent_messagecss1">
            <div class="index_parent_messagecss2">
                <div class="index_parent_messagecss3">

                    <!-- <span>
                        <p>メッセージ</p>
                    </span> -->
                    <p>
                        <img src="<?php echo $absolute_path; ?>static/assets/messageC.png" height=40 alt="メッセージ" class="index_parent_message">
                        <select id="user_select">
                            <option value=""></option>
                            <?php $index_parent_class->getFamilyUser(); ?>
                        </select>
                    </p>
                <div class="login_scroll_bar">

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

                    <form action="" method="POST">
                        <input type="hidden" name="check" value="checked">
                        <p class="index_parent_send">▼ メッセージ送信 ▼</p>
                        <select name="receiver" required>
                            <option value=""></option>
                            <?php $index_parent_class->getFamilyUser(); ?>
                        </select>
                        <input type="text" name="message" required>
                        <button type="submit" class="btn">返信</button>
                    </form>
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
    let norma;
    let norma_deadline;
    let behavioral_goal;
    let reward_point;
    let behavioral_goal_deadline;
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

                     behavioral_goal = '<?php echo $index_parent_class->getBehavioral($index_parent_class->getFamily()[$i]['user_id'])['behavioral_goal'];?>';
                     reward_point = '<?php echo $index_parent_class->getBehavioral($index_parent_class->getFamily()[$i]['user_id'])['reward_point'];?>';
                     behavioral_goal_deadline = '<?php echo $index_parent_class->getBehavioral($index_parent_class->getFamily()[$i]['user_id'])['behavioral_goal_deadline'];?>';

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
                     };
                    norma = <?php echo $index_parent_class->getPointNorma($index_parent_class->getFamily()[$i]['user_id'])['point_norma_amount']; ?>;
                    norma_deadline = '<?php echo $index_parent_class->getPointNorma($index_parent_class->getFamily()[$i]['user_id'])['point_norma_deadline']; ?>';

                <?php } ?>
            }
        <?php } ?>

        document.getElementById('savings').innerHTML = savings;
        document.getElementById('points').innerHTML = points;
        document.getElementById('dayPoint').innerHTML = Math.floor(dayPoint);


        <?php if($_SESSION['goal_select'] == 0){ ?>
            document.getElementById('goal_detail').innerHTML = goal_detail;
            document.getElementById('goal_deadline').innerHTML = goal_deadline;
            document.getElementById('target_amount').innerHTML = target_amount;
        <?php } elseif($_SESSION['goal_select'] == 1){ ?>
            document.getElementById('norma').innerHTML = norma;
            document.getElementById('norma_deadline').innerHTML = norma_deadline;
        <?php } elseif($_SESSION['goal_select'] == 2){ ?>
            document.getElementById('behavioral_goal').innerHTML = behavioral_goal;
            document.getElementById('reward_point').innerHTML = reward_point;
            document.getElementById('behavioral_goal_deadline').innerHTML = behavioral_goal_deadline;
        <?php } ?>



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