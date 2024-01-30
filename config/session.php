<?php
session_start();
date_default_timezone_set("Asia/Tokyo");

$first = null;
if (!strstr($_SERVER["REQUEST_URI"], "login")) {
    if (isset($_COOKIE["first-login"])) {
        $login_count = $_COOKIE["first-login"];
    }

    if (isset($_SESSION["first"])) {
        $first = $_SESSION["first"];
    }

    $today = new DateTime();
    // $today = new DateTime("2023-11-01");
    $date = $today->format("Y-m-d");
    $firstDay = new DateTime("first day of this month");

    if (!strpos($_SERVER["REQUEST_URI"], "goal-setting.php") && $login_count == 1 && $first == "first" && $date == $firstDay->format("Y-m-d")) {
        $_SESSION["first"] = "first";
        header("location: goal-setting.php");
    }

    //ログインセッション処理
    if (isset($_SESSION["select"]) && $_SESSION["select"] === "adult") {
        if (isset($_SESSION["login_times"]) && $_SESSION["login_times"] == "first") {
            header("Location: ./item-edit.php?editItem=5");
        }

        if (isset($_SESSION["user_id"])) :
            $select = $_SESSION["select"];

            $user_data = array(
                "id",
                "username",
                "age",
                "initial_savings",
                "family_id",
                "family_name",
                "name",
                "email",
            );

            $user_where = array(
                "id" => ["=", "i", $_SESSION["user_id"]],
            );

            $user_result = select($db, $user_data, "user", wheres: $user_where);

            $user = array(
                "id" => $_SESSION["user_id"],
                "username" => $user_result[0]["username"],
                "email" => $user_result[0]["email"],
                "age" => $user_result[0]["age"],
                "initial_savings" => $user_result[0]["initial_savings"],
                "family_name" => $user_result[0]["family_name"],
                "first_name" => $user_result[0]["name"],
            );
            $family_id = $user_result[0]["family_id"];
            $initial_savings = $user["initial_savings"];

            $child = array();
            $sql = "SELECT * FROM child WHERE parent = ?";
            $stmt = $db->prepare($sql);

            $stmt->bind_param("i", $user["id"]);
            sql_check($stmt, $db);

            $stmt->bind_result($id, $name, $age, $password, $birthday, $parent, $sex, $child_name, $login_id, $family_id, $points, $login_date, $first_date, $savings, $review_date, $review_flag, $max_lending, $salary);

            $i = 0;
            while ($stmt->fetch()) {
                $child[$i++] = array(
                    "id" => $id,
                    "name" => $name,
                    "age" => $age,
                    "password" => $password,
                    "birthday" => $birthday,
                    "parent" => $parent,
                    "sex" => $sex,
                    "child_name" => $child_name,
                    "login_id" => $login_id,
                    "points" => $points,
                    "login_date" => $login_date,
                    "savings" => $savings,
                    "review_date" => $review_date,
                    "flag" => $review_flag,
                    "max" => $max_lending,
                );
            }

            $user["child"] = $child;

            $stmt->close();
            unset($child);
        endif;
    } elseif (isset($_SESSION["select"]) && $_SESSION["select"] === "child") {
        if (isset($_SESSION["child_id"]) && isset($_SESSION["hash_password"])) :
            $select = $_SESSION["select"];

            $user_data = array(
                "name",
                "age",
                "savings",
                "birthday",
                "parent",
                "points",
                "sex",
                "child_name",
                "login_id",
                "savings",
                "max_lending",
                "review_date",
                "review_flag",
                "family_id",
            );

            $user_where = array(
                "id" => ["=", "i", $_SESSION["child_id"]],
            );

            $user_result = select($db, $user_data, "child", wheres: $user_where);

            $user = array(
                "id" => $_SESSION["child_id"],
                "hash_password" => $_SESSION["hash_password"],
                "name" => $user_result[0]["name"],
                "age" => $user_result[0]["age"],
                "birthday" => $user_result[0]["birthday"],
                "parent" => $user_result[0]["parent"],
                "points" => $user_result[0]["points"],
                "sex" => $user_result[0]["sex"],
                "child_name" => $user_result[0]["child_name"],
                "login_id" => $user_result[0]["login_id"],
                "savings" => $user_result[0]["savings"],
                "max_lending" => $user_result[0]["max_lending"],
                "review_date" => $user_result[0]["review_date"],
                "review_flag" => $user_result[0]["review_flag"],
            );

            $family_id = $user_result[0]["family_id"];
            $savings = $user_result[0]["savings"];
        endif;
    } else {
        header("Location: login.php");
        exit();
    }
}

if (ua_smt() == true) {
    $isPC = false;
} else {
    $isPC = true;
}