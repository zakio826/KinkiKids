<!-- 緊急ミッション画面 -->

<!-- ヘッダー -->
<?php
$page_title = "緊急ミッション";  // イメージ → 金記キッズ｜このページのタイトル
$stylesheet_name = "mission_add.css";
include("../include/header.php");  // appディレクトリ直下であれば、パス先頭のピリオドを１つ消す
?>

<?php
require($absolute_path."lib/mission_class.php");
$mission = new mission($db);

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../accounts/login.php", true , 301);
    exit;
}
$user_id = $_SESSION["user_id"];
$family_id = $_SESSION["family_id"];
$select = $_SESSION["select"];

$missions = $mission->display_mission($family_id);
?>


<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <section>
    <div class="title">
        <h1>緊急ミッション</h1>
    </div>
    <br>
    <div class ="content">
        <?php if ($select === 'adult'): ?>
            <!-- 大人の場合の入力フォーム -->
            <form action="" method="post" class="">
                <p class="choice">子供の選択</p>
                <?php $mission->child_select(); ?><br>
                <label for="">ミッション名</label>
                <input type="text" name="mission_name"><br>
                <label for="">獲得ポイント</label>
                <input type="number" name="mission_get_point"><br>

                <button type="submit" class="">登録</button>
            </form>
        <?php endif; ?>  

        <?php if (empty($missions)): ?>
            <p>緊急ミッションはありません。</p>
        <?php else: ?>
            <ul>
            <?php foreach ($missions as $mission_data): ?>
                    <li>
                        <strong>ミッション名:</strong> <?php echo $mission_data['mission_name']; ?><br>
                        <strong>獲得ポイント:</strong> <?php echo $mission_data['get_point']; ?><br>
                        <strong>担当者:</strong> <?php $mission->person_select($mission_data['mission_id']); ?>
                    </li>
                    <?php if ($select === 'adult'): ?>
                    <form action="" method="post">
                        <!-- 編集　後回し！！ -->
                        <button type="submit" class="">編集</button>
                    </form>
                    <form action="" method="post">
                    <input type="hidden" name="delete_mission_id" value="<?php echo $mission_data['mission_id']; ?>">
                        <button type="submit" class="">削除</button>
                    </form>
                    <?php endif; ?>
                    <?php if ($select === 'child'): ?>
                        <form action="" method="post">
                        <input type="hidden" name="consent_mission_id" value="<?php echo $mission_data['mission_id']; ?>">
                        <?php $mission->m_consent_button($mission_data['mission_id']); ?>
                        </form>
                    <?php endif; ?>
                    <hr>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    </section>
</main>
<!-- ナビゲーションバー -->
<?php include_once("../include/bottom_nav.php") ?>
<!-- フッター -->
<?php include_once("../include/footer.php"); ?>