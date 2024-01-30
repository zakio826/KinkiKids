<?php
require_once("../component/common/dbconnect.php");
include_once("../component/common/functions.php");
include_once("../component/common/session.php");

$id = [
    "parent" => 54,
    "child" => 53,
    "family" => 27,
];

// $id = [
//     "parent" => 20,
//     "child" => 32,
//     "family" => 4,
// ];

$help = [
    "皿洗い" => 59,
    "風呂掃除" => 60,
    "洗濯物" => 61,
    "トイレ掃除" => 62,
    "調理補助" => 63,
    "大掃除" => 64,
];

$spend = [
    "遊び" => 20,
    "日用品" => 22,
];

$insert_data = [
    ["2023-11-05", 1, "遊び", "ジュース", 120],
    ["2023-11-10", 1, "遊び", "ポケモンカード", 150],
    ["2023-11-13", 1, "遊び", "からあげくん", 210],
    ["2023-11-19", 1, "遊び", "ポケモンカード", 150],

    ["2023-12-07", 1, "日用品", "鉛筆", 120],
    ["2023-12-10", 1, "日用品", "消しゴム", 110],
    ["2023-12-20", 1, "遊び", "ポケモンカード", 150],

    ["2023-11-03", 0, [["皿洗い", 10]]],
    ["2023-11-08", 0, [["風呂掃除", 10]]],
    ["2023-11-14", 0, [["トイレ掃除", 10], ["風呂掃除", 10]]],
    ["2023-11-17", 2, [["トンカツの散歩", 50]]],
    ["2023-11-25", 0, [["皿洗い", 10]]],

    ["2023-12-04", 0, [["皿洗い", 20]]],
    ["2023-12-09", 0, [["風呂掃除", 20]]],
    ["2023-12-16", 0, [["トイレ掃除", 20], ["風呂掃除", 20]]],
    ["2023-12-18", 2, [["草むしり", 100]]],
    ["2023-12-23", 0, [["部屋掃除", 20], ["トイレ掃除", 20]]],
    ["2023-12-27", 0, [["風呂掃除", 20]]],
    ["2023-12-29", 0, [["大掃除", 100]]],
    ["2023-12-30", 0, [["大掃除", 100]]],
];

while ($row = current($insert_data)) {
    $date = new DateTime($row[0]);
    // print_r($row);
    // echo "<br>";
    // if ($row[1] == 2) {
    //     echo count($row[2]);
    //     print_r($row[2][0]);
    // }

    if ($row[1] == 1) {
        // 支出
        $data = [
            "date" => ["s", $date->format("Y-m-d")],
            "title" => ["s", $row[3]],
            "amount" => ["i", $row[4]],
            "input_time" => ["s", $date->format("Y-m-d-H-i-s")],
            "user_id" => ["i", $id["parent"]],
            "child_id" => ["i", $id["child"]],
            "family_id" => ["i", $id["family"]],
        ];
        if (array_key_exists($row[2], $spend)) {
            $data["spending_category"] = ["i", $spend[$row[2]]];
        }

        // insert($db, $data, "records");
    } else if ($row[1] == 0) {
        // お手伝い
        $data = [
            "date" => ["s", $date->format("Y-m-d")],
            "input_time" => ["s", $date->format("Y-m-d-H-i-s")],
            "child_id" => ["i", $id["child"]],
            "family_id" => ["i", $id["family"]],
        ];

        for ($i = 0; $i < count($row[2]); $i++) {
            if (array_key_exists($row[2][$i][0], $help)) {
                $data["help_id"] = ["i", $help[$row[2][$i][0]]];
                $data["point"] = ["i", $row[2][$i][1]];
            }

            // insert($db, $data, "points");
        }
    } else if ($row[1] == 2) {
        // 緊急ミッション
        $data = [
            "id" => ["i", $id["parent"]],
            "flag" => ["i", 99],
            "date" => ["s", $date->format("Y-m-d")],
        ];

        for ($i = 0; $i < count($row[2]); $i++) {
                $data["mission"] = ["s", $row[2][$i][0]];
                $data["point"] = ["i", $row[2][$i][1]];

            // insert($line, $data, "EmergencyMission");
        }
    }

    print_r($data);
    echo "<hr>";
    next($insert_data);
}