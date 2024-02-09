<!-- 緊急ミッション画面 -->

<!-- ヘッダー -->
<?php
$page_title = "緊急ミッション";  // イメージ → 金記キッズ｜このページのタイトル
$stylesheet_name = "mission.css";
include("../include/header.php");  // appディレクトリ直下であれば、パス先頭のピリオドを１つ消す
?>

<?php
require($absolute_path."lib/mission_class.php");
$consent = new consent($db);

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../accounts/login.php", true , 301);
    exit;
}
$user_id = $_SESSION["user_id"];
$family_id = $_SESSION["family_id"];
$select = $_SESSION["select"];
?>


<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <section>
    <div class="title">
        <h1>おてつだい承認</h1>
    </div>
    <br>
    <div class ="content">
        <?php foreach ($helps as $help_data): ?>
                <li>
                    <strong>お手伝い名:</strong> <?php echo $help_data['help_name']; ?><br>
                    <strong>獲得ポイント:</strong> <?php echo $help_data['get_point']; ?><br>
                    <strong>担当者</strong>
                    <?php
                        $consent->person_select($help_data['help_id']);
                    ?><br>
                    <form action="" method="post">       
                        <input type="hidden" name="consent_help_id" value="<?php echo $help_data['help_id']; ?>">    
                        <input type="hidden" name="consent_get_point" value="<?php echo $help_data['get_point']; ?>">  
                        <button type="submit">承認する</button>
                    </form>
                </li>
        <?php endforeach; ?>
    </div>
    </section>
</main>

<!-- フッター -->
<?php include_once("../include/footer.php"); ?>