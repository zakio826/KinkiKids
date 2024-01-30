<?php
require_once("./component/common/dbconnect.php");
include_once("./component/common/functions.php");
include_once("./component/common/session.php");

$editItem = filter_input(INPUT_POST, "editItem", FILTER_SANITIZE_NUMBER_INT);
$name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
$point = filter_input(INPUT_POST, "point", FILTER_SANITIZE_SPECIAL_CHARS);

$table_list = ["spending_category", "income_category", "payment_method", "creditcard", "qr", "help"];
$table_name = $table_list[$editItem];

if ($select === "adult") {
    if ($table_name === "help") {
        $sql = "SELECT COUNT(*) FROM {$table_name} WHERE title = ? AND family_id = ?";
    } else {
        $sql = "SELECT COUNT(*) FROM {$table_name} WHERE name = ? AND family_id = ?";
    }
    $stmt = $db->prepare($sql);
    $stmt->bind_param("si", $name, $family_id);
    sql_check($stmt, $db);
    $stmt->bind_result($count);
    $stmt->fetch();

    if ($count > 0) :
        header("Location: ./item-edit.php?editItem=" . $editItem . "&dataOperation=duplicate");
        exit();
    endif;
    $stmt->close();

    if ($table_name !== null) :
        if ($table_name === "help") :
            $sql = "INSERT INTO {$table_name} (title, point, family_id) VALUES(?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("sii", $name, $point, $family_id);
            sql_check($stmt, $db);
            header("Location: ./item-edit.php?editItem=" . $editItem);
        else :
            $sql = "INSERT INTO {$table_name} (name, family_id) VALUES(?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("si", $name, $family_id);
            sql_check($stmt, $db);
            header("Location: ./item-edit.php?editItem=" . $editItem);
        endif;
    else :
        header("Location: ./item-edit.php?editItem=" . ($editItem + 1));
    endif;
} elseif ($select === "child") {
    $sql = "SELECT COUNT(*) FROM {$table_name} WHERE name = ? AND user_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("si", $name, $user["id"]);
    sql_check($stmt, $db);
    $stmt->bind_result($count);
    $stmt->fetch();

    if ($count > 0) :
        header("Location: ./item-edit.php?editItem=" . $editItem . "&dataOperation=duplicate");
        exit();
    endif;
    $stmt->close();

    echo $editItem . $name . $table_name;

    if ($table_name !== null) :
        $cols = array("id");
        $wheres = array("family_id" => ["=", "i", $family_id],);
        $user_id = select($db, $cols, "user", wheres: $wheres);

        $sql = "INSERT INTO {$table_name} (name, user_id, child_id, family_id) VALUES(?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("siii", $name, $user_id, $user["id"], $family_id);
        sql_check($stmt, $db);
        header("Location: ./item-edit.php?editItem=" . $editItem);
    else :
        header("Location: ./item-edit.php?editItem=" . ($editItem + 1));
    endif;
}

exit();
