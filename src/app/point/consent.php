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

$user_id = $_SESSION["user_id"];
$family_id = $_SESSION["family_id"];
$select = $_SESSION["select"];

$helps = $consent->display_consent_help($user_id);
?>


<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <section>
        <div class="mb-3 title">
            <h1>おてつだい承認</h1>
            <h1>まだ承認ボタン押さないで！！！</h1>
        </div>

        <div class ="content">
            <ul>
                <?php foreach ($helps as $help_data): ?>
                    <li>
                        <strong>お手伝い名:</strong><?php echo $help_data['help_name']; ?><br>
                        <strong>獲得ポイント:</strong><?php echo $help_data['get_point']; ?><br>
                        <strong>担当者</strong><?php $consent->person_select($help_data['help_id']); ?><br>

                        <form action="" method="post">       
                            <input type="hidden" name="consent_help_id" value="<?php echo $help_data['help_id']; ?>">    
                            <input type="hidden" name="consent_get_point" value="<?php echo $help_data['get_point']; ?>">  
                            
                            <button type="submit">承認する</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
</main>

<!-- フッター -->
<?php include_once("../include/footer.php"); ?>