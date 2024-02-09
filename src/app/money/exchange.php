<?php
$page_title = "換金";
$stylesheet_name = "";
require_once("../include/header.php");
?>
<?php
// ここでデータベースの接続処理を行う
require($absolute_path."lib/exchange_class.php");
$exchange = new Exchange($db);

if(isset($_SESSION['exchange_error'])) {
    echo '<p class="exchange-error">' . $_SESSION['exchange_error'] . '</p>';
    unset($_SESSION['exchange_error']);
}

// セッションからメッセージがある場合に表示し、セッションをクリア
if(isset($_SESSION['exchange_points'])) {
    echo '<script>alert("' . $_SESSION['exchange_points'] . 'ポイントの換金が完了しました。");</script>' ;
    unset($_SESSION['exchange_points']);
}

?>

<!-- 以下は HTML フォーム部分 -->
<main>
    <form action="" method="POST">
        <select id="user_select" name="selected_user">
            <option value=""></option>
            <?php $exchange->getFamilyUser(); ?>
        </select>
        <input type="int" name="points" placeholder="ポイントを入力" required>
        <button type="submit">ポイント交換</button>
    </form>
<main>
<!-- ナビゲーションバー -->
<?php include_once("../include/bottom_nav.php") ?>

<?php require_once("../include/footer.php"); ?>