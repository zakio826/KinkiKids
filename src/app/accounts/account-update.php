<?php
require_once("./component/common/dbconnect.php");
include_once("./component/common/functions.php");
include_once("./component/common/session.php");

$column_value = filter_input(INPUT_POST, "column_value", FILTER_SANITIZE_SPECIAL_CHARS);
$modify_value = filter_input(INPUT_POST, "modify_value", FILTER_SANITIZE_SPECIAL_CHARS);

if ($select === "adult") {
    if (isset($_POST["now_password"])) :
        $now_password = filter_input(INPUT_POST, "now_password", FILTER_SANITIZE_SPECIAL_CHARS);
        $sql = "SELECT password FROM user WHERE id=? LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $user["id"]);
        sql_check($stmt, $db);
        $stmt->bind_result($hash_password);
        $stmt->fetch();

        //パスワード一致不一致処理
        if (password_verify($now_password, $hash_password)) :
            $modify_value = password_hash($modify_value, PASSWORD_DEFAULT);
        else :
            header("Location: ./account.php?dataOperation=pwerror");
            exit();
        endif;

        $stmt->close();

    endif;

    if ($column_value != "child_count") {
        $sql = "UPDATE user SET {$column_value} = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        if ($column_value === "initial_savings" || $column_value === "age") :
            $stmt->bind_param("ii", $modify_value, $user["id"]);
        else :
            $stmt->bind_param("si", $modify_value, $user["id"]);
        endif;

        if (!$stmt) :
            header("Location: ./account.php?dataOperation=error");
        endif;

        $success = $stmt->execute();

        if (!$success) :
            header("Location: ./account.php?dataOperation=error");
        endif;

        //セッション修正
        if ($column_value === "email") :
            $_SESSION["email"] = $modify_value;
        elseif ($column_value === "username") :
            $_SESSION["username"] = $modify_value;
        elseif ($column_value === "age") :
            $_SESSION["age"] = $modify_value;
        elseif ($column_value === "initial_savings") :
            $_SESSION["initial_savings"] = $modify_value;
        endif;
    } else {
        header("Location: child_register.php");
    }
} else if ($select === "child") {
}

header("Location: ./account.php?dataOperation=update");
exit();
