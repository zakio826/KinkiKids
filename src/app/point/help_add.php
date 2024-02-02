<?php
require("../../../lib/help_class.php");
$help = new help($db);

$user_id = $_SESSION["user_id"];

// ユーザーが登録した目標の情報を取得
$helps = $help->display_help($user_id);
?>

<?php
$page_title = "お手伝い登録";
require_once("../include/header.php");
?>

<main>
    <form action="" method="post" >
        <p>お手伝い名</p>
        <input type="text" name="help_name"><br>
        <p>お手伝い詳細</p>
        <input type="text" name="help_detail"><br>
        <p>獲得ポイント</p>
        <input type="number" name="get_point"><br>
        <button type="submit">登録</button>
    </form>

    <div class="content">
        <h2>登録した目標一覧</h2>

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
            <a href="../accounts/welcome.php" class="btn btn-primary">もどる</a>
        </p>
    </div>
</main>

<?php require_once("../include/footer.php"); ?>
