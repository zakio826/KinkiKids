<?php
$page_title = "お手伝い編集";
$stylesheet_name = "help_edit.css";
require_once("../include/header.php");
?>

<?php // DB接続設定ファイルを読み込む
require_once($absolute_path."lib/help_class.php");

// Helpクラスのインスタンス化
$help = new Help($db);

// POSTされたデータを処理
if (isset($_GET['edit_help_id'])) {
    $edit_help_id = $_GET['edit_help_id'];

    // データベースから該当するお手伝い情報を取得
    $edit_help_info = $help->getHelpInfo($edit_help_id);

    if (!$edit_help_info) {
        // エラー処理など
    }
}

// POSTされたデータを処理
// if (!empty($_POST)) {

//     // フォームからの入力を受け取り、データベースを更新
//     $help->updateHelp($_POST);
//     header('Location: ./help_add.php'); // 編集後にお手伝い一覧ページにリダイレクト
//     exit();
// }
?>

<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <h1>お手伝い編集</h1>
    <div class ="content">
    <form action="" method="post">
        <input type="hidden" name="e_help_id" value="<?php echo $edit_help_id; ?>">

        <label for="help_name">お手伝い名</label>
        <input type="text" id="help_name" name="e_help_name" value="<?php echo htmlspecialchars($edit_help_info['help_name'], ENT_QUOTES, 'UTF-8'); ?>"><br>
        
        <label for="get_point">獲得ポイント</label>
        <input type="number" id="e_get_point" name="e_get_point" value="<?php echo htmlspecialchars($edit_help_info['get_point'], ENT_QUOTES, 'UTF-8'); ?>"><br>

        <strong>担当者</strong>
        <?php $help->e_child_select($edit_help_id); ?><br>
        <button type="submit" class=btn-1>更新</button>
    </form>
    </div>
</main>
<!-- ナビゲーションバー -->
<?php include_once("../include/bottom_nav.php") ?>
<!-- フッター -->
<?php include_once("../include/footer.php"); ?>