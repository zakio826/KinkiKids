<?php
//ログイン後の画面PHP

session_start();
// セッション変数 $_SESSION["loggedin"]を確認。ログイン済だったらウェルカムページへリダイレクト
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: ./login.php");
    exit;
}
?>

<?php
$page_title = "Welcome";
require_once("../include/header.php");
?>

<main>
    <h1 class="my-5">Hi,<b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    <p>
        <a href="./logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
    </p>
    <a href="../index.php">ホーム</a>
    <a href="../point/help_add.php">お手伝い</a>
    <a href="../goal/goal.php">目標</a>
    <a href="../goal/level_of_achievement.php">お金とポイントの状況</a>

</main>

<?php require_once("../include/footer.php"); ?>

