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
                    <p class="mt-1"><?php echo $help_data['help_name']; ?>…<?php echo $help_data['get_point']; ?>ポイント</p>
                    <p class="mt-2"><?php $consent->person_select($help_data['help_id']); ?></p>

                    <form action="" method="post">
                        <div class="btn-group">     
                            <input type="hidden" name="consent_help_id" value="<?php echo $help_data['help_id']; ?>">    
                            <button type="submit" class="btn-1">承認する</button>
                            <button type="submit" class="btn-2">拒否する</button>
                        </div>
                    </form>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
</main>

<!-- フッター -->
<?php include_once("../include/footer.php"); ?>