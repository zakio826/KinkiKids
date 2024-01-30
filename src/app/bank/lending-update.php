<?php
require_once("../component/common/dbconnect.php");
include_once("../component/common/functions.php");
include_once("../component/common/session.php");
// -- スマホ画面のメニューバーのhtml 
include_once("../component/index/sp-tab2.php");

$date = filter_input(INPUT_POST, "date", FILTER_SANITIZE_SPECIAL_CHARS);
$purpose = filter_input(INPUT_POST, "purpose", FILTER_SANITIZE_SPECIAL_CHARS);
$amount = filter_input(INPUT_POST, "amount", FILTER_SANITIZE_NUMBER_INT);
$reason = filter_input(INPUT_POST, "reason", FILTER_SANITIZE_SPECIAL_CHARS);
$repayment = filter_input(INPUT_POST, "repayment", FILTER_SANITIZE_SPECIAL_CHARS);

if ($amount < 0) :
    $_SESSION["r_date"] = $date;
    $_SESSION["r_title"] = $title;
    $_SESSION["r_amount"] = $amount;
    $_SESSION["r_type"] = $type;
    $_SESSION["r_spendingCat"] = $spendingCat;
    $_SESSION["r_paymentMethod"] = $paymentMethod;
    header("Location: ../index.php?dataOperation=numberError");
    exit();
endif;

if (isset($_POST["record_update"]) && $_POST["record_update"] === "更新") :
    $id = filter_input(INPUT_POST, "record_id", FILTER_SANITIZE_SPECIAL_CHARS);
    $sql = "UPDATE debt SET date = ?, purpose = ?, amount = ?, reason = ?, repayment = ? WHERE id = ? AND child_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssissii", $date, $purpose, $amount, $reason, $repayment, $id, $user["id"]);

    sql_check($stmt, $db);
// else :
//     header("Location: ./lending_list.php");
//     exit();
endif;


if (isset($_POST["record_update"]) && $_POST["record_update"] === "更新") :
    header("Location: ./lending_list.php");
endif;

exit();