<!-- トップページ画面親用 -->

<!-- ヘッダー -->
<?php
$page_title = "大人用トップページ";
$stylesheet_name = "index_parent.css";
include("./include/header.php");
?>

<script>
    let select = document.getElementById('user_select');
    let count = <?php echo $message_count; ?>;
    select.addEventListener('change', (e) => {
        let selected_value = document.getElementById('user_select').value;
    });
</script>


<?php

// testpointクラスのインスタンスを作成
require($absolute_path."lib/testpoint_class.php");
$testpoint = new testpoint($db);

require($absolute_path."lib/index_parent_class.php");
$index_parent_class = new index_parent_class($db);


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

    </section>
</main>

<!-- フッター -->
<?php include_once("./include/footer.php"); ?>