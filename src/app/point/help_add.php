<?php
session_start();
require("../../../config/db_connect.php");
require("../../../lib/help_class.php");

$db = new connect();
$help = new help($db);

$user_id = $_SESSION["user_id"];

// ユーザーが登録した目標の情報を取得
$helps = $help->display_help($user_id);

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: ../accounts/login.php");
    exit;
}
?>

<?php
$page_title = "お手伝い登録";
require_once("../include/header.php");
?>

<main>
    <form action="" method="post">
        お手伝い名<input type="text" name="help_name"><br>
        お手伝い詳細<input type="text" name="help_detail"><br>
        獲得ポイント<input type="number" name="get_point"><br>
        <button type="submit">登録</button>
    </form>

    <div class="content">
        <h1>お手伝い一覧</h1>

        <?php if (empty($helps)): ?>
            <p>登録した目標はありません。</p>
        <?php else: ?>
            <ul>
                <?php foreach ($helps as $help): ?>
                    <li>
                        <strong>お手伝い名:</strong> <?php echo $help['help_name']; ?> 円<br>
                        <strong>お手伝い詳細</strong> <?php echo $help['help_detail']; ?><br>
                        <strong>獲得ポイント:</strong> <?php echo $help['get_point']; ?><br>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <p class="mt-3">
            <a href="../accounts/welcome.php" class="btn btn-primary">ホーム</a>
        </p>
    </div>
</main>

<?php require_once("../include/footer.php"); ?>
