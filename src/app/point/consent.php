<!-- 親ユーザーがお手伝いを承認する画面 -->

<!-- ヘッダー -->
<?php
$page_title = "お手伝い承認";  // イメージ → 金記キッズ｜このページのタイトル
$stylesheet_name = "consent.css";
include("../include/header.php");  // appディレクトリ直下であれば、パス先頭のピリオドを１つ消す
?>

<?php
require($absolute_path."lib/conset_class.php");
$consent = new consent($db);

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../accounts/login.php", true , 301);
    exit;
}
$user_id = $_SESSION["user_id"];
$family_id = $_SESSION["family_id"];
$select = $_SESSION["select"];

$helps = $consent->display_consent_help($user_id);
?>


<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <section>
    <div class="title">
        <h1>おてつだい承認</h1>
        <h1>まだ承認ボタン押さないで！！！</h1>
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
                        <input type="hidden" name="consent_help_id" value="<?php echo $help_data['help_id']; ?>">  
                        <button type="submit">承認する</button>
                    </form>
                </li>
        <?php endforeach; ?>
    </div>
    </section>
</main>

<!-- フッター -->
<?php include_once("../include/footer.php"); ?>