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
$options = $help->narrow_down();

if (isset($_POST["narrow"]) && !empty($_POST["narrow"])) {
    $selectedUserId = $_POST["narrow"];
    $helps = $help->getHelpsByUserId($selectedUserId);
} else {
    // $_POST["narrow"] に何もない場合は全てのお手伝い項目を表示する
    $family_id = $_SESSION["family_id"];
    $helps = $help->display_help($family_id);
}
?>

<main>
    <div class="mb-3 title"><h1>おてつだい</h1></div>

    <div class ="mb-3 content">
        <?php if ($select === 'adult'): ?>
            <!-- 大人の場合のフォーム -->
            <form action="" method="post" class="adult-form">
                <p class="choice">子供の選択</p>
                <?php $help->child_select(); ?><br>
                <label for="help_name">お手伝い名</label>
                <input type="text" name="help_name"><br>
                <label for="get_point">獲得ポイント</label>
                <input type="number" name="get_point"><br>

                <button type="submit" class="btn-touroku">登録</button>
            </form>
        <?php elseif ($select === 'child'): ?>
            <!-- 子供の場合のフォーム -->
            <!-- 別のフォームやメッセージを表示するなど、必要に応じて変更してください -->
            <p>子供向けのフォームやメッセージを表示</p>
        <?php else: ?>
            <!-- 予期せぬケースに備えてデフォルトの表示 -->
            <p>選択されたユーザータイプに対応するフォームがありません。</p>
        <?php endif; ?>  
        <h1>お手伝い一覧</h1>
    <?php if ($select === 'adult'): ?>
       
            <form action="" method="post">
                <select name="narrow">
                    <?php foreach ($options as $user): ?>
                        <option value="<?php echo $user['user_id']; ?>"><?php echo $user['first_name']; ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn-1">絞り込む</button>
            </form>
       
    <?php endif; ?>

    
        <div class="scroll_bar">

        <?php if (empty($helps)): ?>
            <p>お手伝いはありません。</p>
        <?php else: ?>
            <?php foreach ($helps as $help_data): ?>
                <ul>
                    <li>
                        <!--<strong>お手伝い名:</strong>--> <?php echo $help_data['help_name']; ?><br>
        
                        <?php if ($select === 'child' and isset($help_data['help_detail'])) : ?>
                        <strong>お手伝い詳細:</strong><?php $help->person_select($help_data['help_detail']); ?><br>
                        <?php endif; ?>

                        <!--<strong>獲得ポイント:</strong>--> <?php echo $help_data['get_point']; ?><strong>pt</strong>
                        &emsp; 
                        <strong>担当者 :</strong>&ensp;  <?php $help->person_select($help_data['help_id']); ?><br>
                    </li>
                </ul>

                    
                <?php if ($select === 'adult'): ?>
                    <div class="btn-group">
                    <form action="help_edit.php" method="get">
                        <input type="hidden" name="edit_help_id" value="<?php echo $help_data['help_id']; ?>">
                        <button type="submit" class="btn-1">編集</button>
                    </form>
                    
                    <form action="" method="post">
                        <input type="hidden" name="delete_help_id" value="<?php echo $help_data['help_id']; ?>">
                        <button type="submit" class="btn-2">削除</button>
                    </form>

                    <hr>
                    </div>
                <?php else : ?>
                    <hr>

                    <form action="" method="post">
                        <input type="hidden" name="consent_help_id" value="<?php echo $help_data['help_id']; ?>">
                        <?php $help->consent_button($help_data['help_id']); ?>
                    </form>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
        </div>
        <p class="mt-3"><a href="consent.php" class="btn btn-primary">承認ページ</a></p>
        <!-- <p class="mt-3"><a href="../welcome.php" class="btn btn-primary">もどる</a></p> -->
        
    </div>
</main>

<?php require_once("../include/footer.php"); ?>