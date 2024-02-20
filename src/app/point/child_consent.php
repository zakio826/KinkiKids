<!-- ポイント受け取り画面 -->

<!-- ヘッダー -->
<?php
$page_title = "ポイント獲得";  // イメージ → 金記キッズ｜このページのタイトル
$stylesheet_name = "child_consent.css";
include("../include/header.php");  // appディレクトリ直下であれば、パス先頭のピリオドを１つ消す
?>

<?php
require($absolute_path."lib/child_consent_class.php");
$chid_consent = new child_consent($db);

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
        <div class="frame">
            <div class="point-title">
                <h1>ポイント獲得</h1>
            </div>
            <br>
            <div class ="content">
                <?php
                $f1 = $chid_consent->getHelps($user_id);
                $f2 = $chid_consent->getmissions($user_id);

                if($f1 && $f2){
                    echo "<p>獲得できるポイントはありません</p>";
                }
                ?>
            </div>
        </div>
    
    </section>
</main>
<!-- ナビゲーションバー -->
<?php include_once("../include/bottom_nav.php") ?>
<!-- フッター -->
<?php include_once("../include/footer.php"); ?>