<?php
require_once("./component/common/dbconnect.php");
include_once("./component/common/functions.php");
include_once("./component/common/session.php");

$table_list = ["spending_category", "income_category", "payment_method", "creditcard", "qr", "help"];

$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_SPECIAL_CHARS);
$editItem = filter_input(INPUT_POST, "editItem", FILTER_SANITIZE_NUMBER_INT);
$name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
$point = filter_input(INPUT_POST, "point", FILTER_VALIDATE_INT);

$table_name = $table_list[$editItem];

if ($table_name === "help") :
    $sql = "SELECT COUNT(*) FROM {$table_name} WHERE name = ? AND point = ? AND family_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sii", $name, $point, $family_id);
    sql_check($stmt, $db);
    $stmt->bind_result($count);
    $stmt->fetch();
else:
    $sql = "SELECT COUNT(*) FROM {$table_name} WHERE name = ? AND family_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("si", $name, $family_id);
    sql_check($stmt, $db);
    $stmt->bind_result($count);
    $stmt->fetch();
endif;

if ($count > 0) :
    header("Location: ./item-edit.php?editItem=" . $editItem . "&dataOperation=duplicate");
    exit();
endif;
$stmt->close();

if ($table_name === "help") :
    $sql = "UPDATE {$table_name} SET name=?, point=? WHERE id=? AND family_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("siii", $name, $point, $id, $family_id);
else :
    $sql = "UPDATE {$table_name} SET name=? WHERE id=? AND family_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sii", $name, $id, $family_id);
endif;

if (!$stmt) :
    header("Location: ./item-edit.php?editItem=" . $editItem . "&dataOperation=error");
    exit();
endif;

$success = $stmt->execute();

if (!$success) :
    header("Location: ./item-edit.php?editItem=" . $editItem . "&dataOperation=error");
    exit();
endif;

header("Location: ./item-edit.php?editItem=" . $editItem . "&dataOperation=update");
exit();
