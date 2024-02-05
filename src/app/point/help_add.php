
<?php
$page_title = "お手伝い登録";
require_once("../include/header.php");
?>
<?php
require("../../../lib/help_class.php");
$help = new help($db);

$user_id = $_SESSION["user_id"];
$family_id = $_SESSION["family_id"];
$select = $_SESSION["select"];

// ユーザーが登録した目標の情報を取得
$helps = $help->display_help($family_id);


if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../accounts/login.php", true , 301);
    exit;
}
?>

<main>
    <?php if ($select === 'adult'): ?>
        <!-- 大人の場合のフォーム -->
        <form action="" method="post">
            お手伝い名<input type="text" name="help_name"><br>
            お手伝い詳細<input type="text" name="help_detail"><br>
            獲得ポイント<input type="number" name="get_point"><br>
            <button type="submit">登録</button>
        </form>
    <?php elseif ($select === 'child'): ?>
        <!-- 子供の場合のフォーム -->
        <!-- 別のフォームやメッセージを表示するなど、必要に応じて変更してください -->
        <p>子供向けのフォームやメッセージを表示</p>
    <?php else: ?>
        <!-- 予期せぬケースに備えてデフォルトの表示 -->
        <p>選択されたユーザータイプに対応するフォームがありません。</p>
        <ul>
            <?php foreach ($helps as $help): ?>
                <li>
                    <strong>お手伝い名:</strong> <?php echo $help['help_name']; ?><br>
                    <strong>お手伝い詳細</strong> <?php echo $help['help_detail']; ?><br>
                    <strong>獲得ポイント:</strong> <?php echo $help['get_point']; ?><br>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

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
                        <?php if ($select === 'adult'): ?>
                        <form action="" method="post">
                            <input type="hidden" name="delete_help_id" value="<?php echo $help['help_id']; ?>">
                            <button type="submit">削除</button>
                        </form>
                        <form action="" method="post">
                            <input type="hidden" name="edit_help_id" value="<?php echo $help['help_id']; ?>">
                            <button type="submit">編集</button>
                        </form>
                    <?php endif; ?>
                
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <p class="mt-3">
            <a href="../welcome.php" class="btn btn-primary">もどる</a>
        </p>
    </div>
</main>

<?php require_once("../include/footer.php"); ?>
