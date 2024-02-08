<?php
$page_title = "お手伝い登録";
$stylesheet_name = "help_add.css";
require_once("../include/header.php");
?>

<?php
require($absolute_path."lib/help_class.php");
$help = new help($db);

$user_id = $_SESSION["user_id"];
$family_id = $_SESSION["family_id"];
$select = $_SESSION["select"];

// ユーザーが登録した目標の情報を取得
$helps = $help->display_help($family_id);
?>

<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <div class="title">
        <h1>おてつだい</h1>
    </div>

    <br>

    <div class ="content">
        <?php if ($select === 'adult'): ?>
            <!-- 大人の場合のフォーム -->
            <form action="" method="post" class="adult-form">
                担当者　<?php $help->child_select(); ?><br>
                <label for="help_name">お手伝い名</label>
                <input type="text" name="help_name"><br>
                <label for="help_detail">お手伝い詳細</label>
                <input type="text" name="help_detail"><br>
                <label for="get_point">獲得ポイント</label>
                <input type="number" name="get_point"><br>

                <button type="submit" class="btn-1">登録</button>
            </form>
        <?php elseif ($select === 'child'): ?>
            <!-- 子供の場合のフォーム -->
            <!-- 別のフォームやメッセージを表示するなど、必要に応じて変更してください -->
            <p>子供向けのフォームやメッセージを表示</p>
        <?php else: ?>
            <!-- 予期せぬケースに備えてデフォルトの表示 -->
            <p>選択されたユーザータイプに対応するフォームがありません。</p>
        <?php endif; ?>
    </div>
   
    <br>

    <div class="content">
        <h2>お手伝い一覧</h2>

        <?php if (empty($helps)): ?>
            <p>お手伝いはありません。</p>
        <?php else: ?>
            <ul>
                <?php foreach ($helps as $help_data): ?>
                    <li>
                        <strong>お手伝い名:</strong> <?php echo $help_data['help_name']; ?><br>
                        <strong>お手伝い詳細</strong> <?php echo $help_data['help_detail']; ?><br>
                        <strong>獲得ポイント:</strong> <?php echo $help_data['get_point']; ?><br>
                        <strong>担当者</strong>
                        <?php
                            $help->person_select($help_data['help_id']);
                        ?><br>
                    <hr>
                    <?php if ($select === 'adult'): ?>
                        <form action="help_edit.php" method="get">
                            <input type="hidden" name="edit_help_id" value="<?php echo $help_data['help_id']; ?>">
                            <button type="submit">編集</button>
                        </form>
                        
                        <form action="" method="post">
                            <input type="hidden" name="delete_help_id" value="<?php echo $help_data['help_id']; ?>">
                            <button type="submit">削除</button>
                        </form>
                    <?php endif; ?>

                    <?php if ($select === 'child'): ?>
                        <form action="" method="post">
                            <input type="hidden" name="consent_help_id" value="<?php echo $help_data['help_id']; ?>">
                            <?php
                            $help->consent_button($help_data['help_id']);
                            ?>
                        </form>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <p class="mt-3"><a href="../welcome.php" class="btn btn-primary">もどる</a></p>
    </div>
</main>

<?php require_once("../include/footer.php"); ?>
