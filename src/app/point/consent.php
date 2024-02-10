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

if ($select === 'child'):
    header("Location: ./help_add.php");
    exit();
endif;

$user_id = $_SESSION["user_id"];
$family_id = $_SESSION["family_id"];
$select = $_SESSION["select"];

$helps = $consent->display_consent_help($user_id);
$debts = $consent->display_consent_debt($family_id);
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
                        <button type="submit" class="btn-syounin">承認する</button>
                    </form>
                </li>
        <?php endforeach; ?>
    </div>
    <div class ="content">
        <?php foreach ($debts as $debt_data): ?>
            <li>
                <strong>内容:</strong> <?php echo $debt_data['contents']; ?><br>
                <strong>金額:</strong> <?php echo $debt_data['debt_amount']; ?><br>
                <strong>返済日:</strong> <?php echo $debt_data['repayment_date']; ?><br>
                <strong>分割回数:</strong> <?php echo $debt_data['installments']; ?><br>
                <strong>担当者</strong>
                <?php
                    $consent->debt_select($debt_data['debt_id']);
                ?><br>
                <form action="" method="post">
                    <input type="int" name="interest" placeholder="利率を入力してください" required><span>%</span><br>
                    <input type="hidden" name="consent_debt_id" value="<?php echo $debt_data['debt_id']; ?>">    
                    <button type="submit" class="btn-syounin">承認する</button>
                </form>
            </li>
        <?php endforeach; ?>
    </div>
    </section>
        <!-- ボトムナビゲーションバー -->
        <?php include_once("../include/bottom_nav.php") ?>
</main>
<!-- ナビゲーションバー -->
<?php include_once("../include/bottom_nav.php") ?>
<!-- フッター -->
<?php include_once("../include/footer.php"); ?>