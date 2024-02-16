<?php
$page_title = "換金";
$stylesheet_name = "exchange.css";
require_once("../include/header.php");
?>
<?php
// ここでデータベースの接続処理を行う
require($absolute_path."lib/exchange_class.php");
$exchange = new Exchange($db);

$select = $_SESSION["select"];

if ($select !== 'adult'):
    header("Location: ../index.php");
    exit();
endif;




// セッションからメッセージがある場合に表示し、セッションをクリア
if(isset($_SESSION['exchange_points'])) {
    echo '<script>alert("' . $_SESSION['exchange_points'] . 'ポイントの換金が完了しました。");</script>' ;
    unset($_SESSION['exchange_points']);
}

?>

<!-- 以下は HTML フォーム部分 -->
<main>
<div class="mb-3 title"><h1>換金</h1></div>
    <form action="" method="POST">
        <select id="user_select" name="selected_user">
            <option value=""></option>
            <?php $exchange->getFamilyUser(); ?>
        </select>
        <br>
        <?php
        if(isset($_SESSION['child_error'])){
            echo '<p class="child-error">' . $_SESSION['child_error'] . '</p>';
            unset($_SESSION['child_error']);
        }
        ?>
        <input type="number" name="points" class="exchange_pointinput" placeholder="ポイントを入力">
        <?php
        if(isset($_SESSION['exchange_error'])) {
            echo '<p class="exchange-error">' . $_SESSION['exchange_error'] . '</p>';
            unset($_SESSION['exchange_error']);
        }else if(isset($_SESSION['point_error'])){
            echo '<p class="point-error">' . $_SESSION['point_error'] . '</p>';
            unset($_SESSION['point_error']);
        }

        ?>
        <button type="submit" class="btn-kankin">ポイント交換</button>
    </form>
<main>
<!-- ナビゲーションバー -->
<?php include_once("../include/bottom_nav.php") ?>

<?php require_once("../include/footer.php"); ?>