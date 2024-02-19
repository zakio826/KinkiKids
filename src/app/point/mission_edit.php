<?php
$page_title = "ミッション編集";
$stylesheet_name = "mission_edit.css";
require_once("../include/header.php");
?>

<?php // DB接続設定ファイルを読み込む
require_once($absolute_path."lib/mission_class.php");

// Helpクラスのインスタンス化
$mission = new mission($db);

// POSTされたデータを処理
if (isset($_GET['edit_mission_id'])) {
    $edit_mission_id = $_GET['edit_mission_id'];

    // データベースから該当するお手伝い情報を取得
    $edit_mission_info = $mission->getmissionInfo($edit_mission_id);

    if (!$edit_mission_info) {
        // エラー処理など
    }
}
?>

<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <h1>ミッション編集</h1>
    <div class ="content">
    <form action="" method="post">
        <input type="hidden" name="e_mission_id" value="<?php echo $edit_mission_id; ?>">

        <label for="help_name">お手伝い名</label>
        <input type="text" id="mission_name" name="e_mission_name" value="<?php echo htmlspecialchars($edit_mission_info['mission_name'], ENT_QUOTES, 'UTF-8'); ?>"><br>
        
        <label for="get_point">獲得ポイント</label>
        <input type="number" id="e_get_point" name="e_get_point" value="<?php echo htmlspecialchars($edit_mission_info['get_point'], ENT_QUOTES, 'UTF-8'); ?>"><br>

        <strong>担当者</strong>
        <?php $mission->e_child_select($edit_mission_id); ?><br>
        <button type="submit" class="btn-1">更新</button>
    </form>
</div>
</main>
<!-- ナビゲーションバー -->
<?php include_once("../include/bottom_nav.php") ?>
<!-- フッター -->
<?php include_once("../include/footer.php"); ?>