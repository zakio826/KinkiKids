<?php
require_once("./component/common/dbconnect.php");
include_once("./component/common/functions.php");
include_once("./component/common/session.php");

if (isset($_GET["id"]) && isset($_GET["from"])) :
    $id = $_GET["id"];

    if ($_GET["from"] === "index") :
        $backpage = $_GET["from"] . ".php?";
        if ($select === "adult") {
            $sql = "DELETE FROM records WHERE id = ? AND user_id = ?";
        } else if ($select === "child") {
            $sql = "DELETE FROM records WHERE id = ? AND child_id = ?";
        }
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $id, $user["id"]);

    elseif ($_GET["from"] === "item-edit" && isset($_GET["table_number"])) :
        $table_number = $_GET["table_number"];
        $table_list = ["spending_category", "income_category", "payment_method", "creditcard", "qr", "help"];
        $table_name = $table_list[$table_number];
        $backpage = $_GET["from"] . ".php?editItem=" . $table_number . "&";
        //SQLコードの発行
        $sql = "DELETE FROM {$table_name} WHERE id = ? AND family_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $id, $family_id);
    endif;
    sql_check($stmt, $db);

    header("Location: ./" . $backpage . "dataOperation=delete");
    exit();

else :
    header("Location: ./" . $backpage . "dataOperation=error");
    exit();

endif;
