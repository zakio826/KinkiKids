<?php
$page_title = "";
$stylesheet_name = "repayment.css";
require_once("../include/header.php");
?>
<?php
require($absolute_path."lib/repayment_class.php");
$user_id = $_SESSION["user_id"];
$family_id = $_SESSION["family_id"];
$repayment = new repayment($db, $user_id, $family_id);
$debt_id = isset($_GET['debt_id']) ? $_GET['debt_id'] : null;
?>

<main>
    <div class ="content">
        <?php
        // debt_idが渡されている場合、その借金の情報を表示
        if ($debt_id) {
            $debt_info = $repayment->getDebtInfo($debt_id);

            if ($debt_info) {
                echo '<strong>内容:</strong> ' . $debt_info['contents'] . '<br>';
                echo '<strong>最低返済金額:</strong> ' . $debt_info['repayment_installments'] . "円<br>";
                echo '<strong>借金残額:</strong> ' . $debt_info['repayment_amount'] . "円<br>";
                echo '<form action="" method="post">';
                echo '<input type="hidden" name="consent_repayment" value="' . $debt_id . '">';
                echo '<button type="submit" class="btn-hensai">返済する</button>';
                echo '</form>';
            } else {
                echo '指定された借金が見つかりません';
            }
        } else {
            echo '借金IDが指定されていません';
        }
        ?>
    </div>
</main>
<!-- ナビゲーションバー -->
<?php include_once("../include/bottom_nav.php") ?>
<?php require_once("../include/footer.php"); ?>