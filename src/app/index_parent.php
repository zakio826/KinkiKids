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

$family_count = $index_parent_class->getFamilyCount();


//family_addでのsessionがあれば完了の通知出す
if (isset($_SESSION['family_success']) && $_SESSION['family_success']) {
    echo '<script>alert("' . $_SESSION['family_count'] . '人の登録が完了しました。");</script>';
    unset($_SESSION['family_success'], $_SESSION['family_count']);
}
?>

<!-- ナビゲーションバー -->
<?php include_once("./include/nav_bar.php") ?>

<main>
    <!-- ロゴ -->
    <header class="position-relative h-25" style="padding-top: 4rem;">
        <img class="d-block mx-auto py-3" src="<?php echo $absolute_path; ?>static/assets/logo.png" height="120">
    </header>
    <section class="position-relative h-75">
        <select id="user_select">
            <option value=""></option>
            <?php $index_parent_class->getFamilyUser(); ?>
        </select>
        <p id="order-string"></p>

    </section>
    <!-- ナビゲーションバー -->
    <?php include_once("./include/bottom_nav.php") ?>

</main>

<script>
    let select = document.getElementById('user_select');
    select.addEventListener('change', (e) => {
        goal = [];
        let selected_value = document.getElementById('user_select').value;
        <?php for($i=0;$i<$family_count;$i++){ ?>
            if(selected_value==<?php echo htmlspecialchars($index_parent_class->getFamily($i)['user_id']); ?>){
                goal.push('<?php echo htmlspecialchars($index_parent_class->getFamily($i)['goal_detail']);?>');

            }
        <?php } ?>
        let str = goal.join('<br>');
        document.getElementById('order-string').innerHTML = str;


    });
</script>


<!-- フッター -->
<?php include_once("./include/footer.php"); ?>