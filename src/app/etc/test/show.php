<?php
require_once("../component/common/dbconnect.php");
include_once("../component/common/functions.php");
include_once("../component/common/session.php");

// $columns = array(
//     // "id",
//     "date",
//     "title",
//     "amount",
//     // "spending_category",
//     // "income_category",
//     // "type",
//     // "payment_method",
//     // "creditcard",
//     // "qr",
//     "memo",
//     // "input_time",
//     // "user_id",
//     "child_id",
//     // "family_id",
// );

// $joins = array(
//     // "spending_category" => "records.spending_category = spending_category.id",
//     // "income_category" => "records.income_category = income_category.id",
//     // "payment_method" => "records.payment_method = payment_method.id",
//     // "creditcard" => "records.creditcard = creditcard.id",
//     // "child" => "records.child_id = child.id",
//     // "qr" => "records.qr = qr.id",
// );

// $wheres = array(
//     "family_id" => ["=", "i", 4],
//     // "amount" => ["<", "i", 100],
// );

// $columns = array(
//     "records.id",
//     "records.date",
//     "records.title",
//     "records.amount",
//     "spending_category.name",
//     "income_category.name",
//     "records.type",
//     "payment_method.name",
//     "creditcard.name",
//     "qr.name",
//     "records.memo",
//     "records.input_time",
//     "child.name",
//     "child.id",
// );

// $joins = array(
//     "spending_category" => "records.spending_category = spending_category.id",
//     "income_category" => "records.income_category = income_category.id",
//     "payment_method" => "records.payment_method = payment_method.id",
//     "creditcard" => "records.creditcard = creditcard.id",
//     "child" => "records.child_id = child.id",
//     "qr" => "records.qr = qr.id",
// );

// $wheres = array(
//     // "records.family_id" => ["=", "i", 4],
//     // "records.date" => ["LIKE", "s", "2023-09%"],
// );

// $group_order = array(
//     "group" => "child.name",
//     "order" => ["child.id", true],
// );

// $result = select($db, $columns, "records", joins: $joins, wheres: $wheres, group_order: $group_order);

// for ($i = 0; $i < count($result); $i++) {
//     print_r($result[$i]);
//     echo "<br><hr>";
// }

$columns = array(
    "id",
    "name",
    "child_id",
    "family_id",
    "point",
);

$result = select($db, $columns, "exchange");

for ($i = 0; $i < count($result); $i++) {
    print_r($result[$i]);
    echo "<br><hr>";
}

echo "<hr>";

$exchange = [
    "テレビ" => 50,
    "ボール" => 10,
    "色鉛筆" => 5,
    "ぬいぐるみ" => 25,
    "お菓子" => 5,
];



foreach ($exchange as $key => $value) {
    # code...
    $update = array(
        "point" => ["i", $value]
    );

    $wheres = array(
        "name" => ["=", "s", $key],
    );
    // $insert_data = array(
    //     "name" => ["s", $key],
    //     "child_id" => ["i", 32],
    //     "family_id" => ["i", 3],
    // );

    // update($db, $update, "exchange", wheres:$wheres);
    // insert($db, $insert_data, "exchange");
}

$result = select($db, $columns, "exchange");

for ($i = 0; $i < count($result); $i++) {
    print_r($result[$i]);
    echo "<br><hr>";
}

// $columns = array(
//     "id",
//     "date",
//     "mission",
//     "point",
// );

// $col = array(
//     "mission" => ["s","ccc"],
//     "point" => ["i",0],
// );

// $wheres = array(
//     "id" => ["=", "i", 19],
//     // "records.date" => ["LIKE", "s", "2023-09%"],
// );

// $order = ["number", false];

// $result = select($line, $columns, "EmergencyMission", wheres: $wheres);

// for ($i = 0; $i < count($result); $i++) {
//     print_r($result[$i]);
//     echo "<br><hr>";
// }

echo "<hr>";
// update($line, "EmergencyMission", $col, wheres:$wheres, order:$order, limits:1);

// $result = select($line, $columns, "EmergencyMission", wheres: $wheres);

// for ($i = 0; $i < count($result); $i++) {
//     print_r($result[$i]);
//     echo "<br><hr>";
// }

// $where_month = "2023-08%";

// $review_data = array(
//     "COUNT(*)",
//     "date",
// );

// $review_where = array(
//     "family_id" => ["=", "i", $family_id],
//     "date" => ["LIKE", "s", $where_month],
// );

// $review_order = array(
//     "group" => "date",
//     "order" => ["date", false],
// );

// $group_result = select($db, $review_data, "records", wheres: $review_where, group_order: $review_order);

$child_data = array(
    "child_name" => "child_name",
    "password",
);

$child_where = array(
    "family_id" => ["=", "i", 3],
);

$child_order = array(
    // "group" => "date",
    // "order" => ["date", false],
);

$group_result = select($db, $child_data, "child", wheres: $child_where, group_order: $child_order);

$test = 1234;
for ($i = 0; $i < count($group_result); $i++) {
    print_r($group_result[$i]);
    echo "<br>";
    echo $group_result[$i]["child_name"];
    echo "<br>";
    echo $group_result[$i]["password"];
    if (password_verify($test, $group_result[$i]["password"])) {
        echo "valid";
    } else {
        echo "invalid";
    }
    echo "<br><hr>";
}
