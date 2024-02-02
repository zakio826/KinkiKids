<!-- ウェルカム画面 -->

<!-- ヘッダー -->
<?php
$page_title = "Welcome";
require_once("../include/header.php");
?>

<main>
    <!-- ナビゲーションバー -->
    <?php include("./include/nav_bar.php") ?>
    
    <h1 class="my-5">Hi,<b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    <p>
        <a href="./logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
    </p>
    <a href="../index.php">ホーム</a>
    <a href="../point/help_add.php">お手伝い</a>
    <a href="../goal/goal.php">目標</a>
</main>

<!-- フッター -->
<?php require_once("../include/footer.php"); ?>

