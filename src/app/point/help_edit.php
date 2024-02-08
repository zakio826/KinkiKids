<?php
$page_title = "お手伝い編集";
require_once("../include/header.php");
?>

<?php // DB接続設定ファイルを読み込む
require_once($absolute_path."lib/help_class.php");

try {
    $db = new PDO("mysql:host=localhost;dbname=kinkikids", "root", "");

    // エラーモードを例外モードに設定
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // エラー処理など
    echo "データベース接続エラー: " . $e->getMessage();
    exit;
}

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
    header('Location: ./help_add.php'); // 編集後にお手伝い一覧ページにリダイレクト
    exit();
}
?>

<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <h1>お手伝い編集</h1>
    <h1>バグるのでまだ更新しないで！！！！</h1>

    <form action="" method="post">
        <input type="hidden" name="help_id" value="<?php echo $edit_help_id; ?>">
        <label for="help_name">お手伝い名：</label>

        <input type="text" id="help_name" name="help_name" value="<?php echo htmlspecialchars($edit_help_info['help_name'], ENT_QUOTES, 'UTF-8'); ?>"><br>
        <label for="get_point">獲得ポイント：</label>
        
        <input type="number" id="get_point" name="get_point" value="<?php echo htmlspecialchars($edit_help_info['get_point'], ENT_QUOTES, 'UTF-8'); ?>"><br>
        <button type="submit">更新</button>
    </form>
</main>

<!-- フッター -->
<?php include_once("../include/footer.php"); ?>