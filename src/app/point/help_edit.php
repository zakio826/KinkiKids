<?php
$page_title = "お手伝い登録";
require_once("../include/header.php");
?>
<?php // DB接続設定ファイルを読み込む
require_once("../../../lib/help_class.php");

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
if (!empty($_POST)) {
    // フォームからの入力を受け取り、データベースを更新
    $help->updateHelp($_POST);
    header('Location: help_add.php'); // 編集後にお手伝い一覧ページにリダイレクト
    exit();
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お手伝い編集</title>
</head>
<body>
    <h1>お手伝い編集</h1>
    <form action="" method="post">
        <input type="hidden" name="help_id" value="<?php echo $edit_help_id; ?>">
        <label for="help_name">お手伝い名：</label>
        <input type="text" id="help_name" name="help_name" value="<?php echo htmlspecialchars($edit_help_info['help_name'], ENT_QUOTES, 'UTF-8'); ?>"><br>
        <label for="help_detail">お手伝い詳細：</label>
        <input type="text" id="help_detail" name="help_detail" value="<?php echo htmlspecialchars($edit_help_info['help_detail'], ENT_QUOTES, 'UTF-8'); ?>"><br>
        <label for="get_point">獲得ポイント：</label>
        <input type="number" id="get_point" name="get_point" value="<?php echo htmlspecialchars($edit_help_info['get_point'], ENT_QUOTES, 'UTF-8'); ?>"><br>
        <button type="submit">更新</button>
    </form>
</body>
</html>
