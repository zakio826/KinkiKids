<!-- 親ユーザーがミッションを承認する画面 -->

<!-- ヘッダー -->
<?php
$page_title = "ミッション承認";  // イメージ → 金記キッズ｜このページのタイトル
$stylesheet_name = "mission_consent.css";
include("../include/header.php");  // appディレクトリ直下であれば、パス先頭のピリオドを１つ消す
?>

<?php
require($absolute_path."lib/conset_class.php");
$consent = new consent($db);

$user_id = $_SESSION["user_id"];
$family_id = $_SESSION["family_id"];
$select = $_SESSION["select"];

$missions = $consent->display_consent_mission($user_id);
?>


<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <section>
    <div class="title">
        <h1>ミッション承認</h1>
    </div>
    <br>
    <div class ="content">
        <?php foreach ($missions as $mission_data): ?>
                <li>
                    <strong>ミッション名:</strong> <?php echo $mission_data['mission_name']; ?><br>
                    <strong>獲得ポイント:</strong> <?php echo $mission_data['get_point']; ?><br>
                    <strong>担当者</strong>
                    <?php
                        $consent->m_person_select($mission_data['mission_id']);
                    ?><br>
                    <form action="" method="post">       
                        <input type="hidden" name="consent_mission_id" value="<?php echo $mission_data['mission_id']; ?>">    
                        <button type="submit">承認する</button>
                    </form>
                </li>
        <?php endforeach; ?>
    </div>
    </section>
</main>
<!-- ナビゲーションバー -->
<?php include_once("./include/bottom_nav.php") ?>
<!-- フッター -->
<?php include_once("../include/footer.php"); ?>