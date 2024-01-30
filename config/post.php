<?php
require_once("./dbconnect.php");
include_once("./functions.php");

$request_raw_data = file_get_contents("php://input");
$data = json_decode($request_raw_data);

if (isset($data->from) && $data->from == "review") {
} else {
    $headers = [
        "Authorization: Bearer " . $channelToken,
        "Content-Type: application/json",
    ];

    $col = array(
        "child_name",
        "points",
    );

    $wheres = array(
        "id" => ["=", "i", $data->child_id],
    );

    $result = select($db, $col, "child", wheres: $wheres, limits: 1);


    $line_col = array(
        "UID",
    );

    $line_wheres = array(
        "id" => ["=", "i", $data->parent_id],
    );

    $line_result = select($line, $line_col, "LINEdatabase", wheres: $line_wheres, limits: 1);


    if ($data->type === "normal") {
        $insert_data = array(
            "date" => ["s", $data->mission->today],
            "help_id" => ["i", $data->mission->id],
            "family_id" => ["i", $data->mission->family_id],
            "child_id" => ["i", $data->child_id],
            "input_time" => ["s", $data->mission->input_time],
            "point" => ["i", $data->mission->point],
        );

        insert($db, $insert_data, "points");


        $point_data = array(
            "points" => ["i", $result[0]["points"] + $data->mission->point],
        );

        $point_where = array(
            "id" => ["=", "i", $data->child_id],
        );

        update($db, $point_data, "child", wheres: $point_where);


        $post = [
            "to" => $line_result[0]["UID"],
            "messages" => [
                [
                    "type" => "text",
                    "text" => "「" . $data->mission->title . "」を" . $result[0]["child_name"] . "が達成しました。",
                ],
            ],
        ];
    } else if ($data->type === "emergent") {
        $update_data = array(
            "flag" => ["i", -1],
        );

        $where = array(
            "id" => ["=", "i", $data->parent_id],
            "flag" => ["=", "i", 0],
        );

        $order = ["number", true];

        update($line, $update_data, "EmergencyMission", $where, order: $order, limits: 1);


        $point_data = array(
            "points" => ["i", $result[0]["points"] + $data->mission->point],
        );

        $point_where = array(
            "id" => ["=", "i", $data->child_id],
        );

        update($db, $point_data, "child", wheres: $point_where);

        $mission_col = array(
            "mission",
        );

        $mission_wheres = array(
            // "id" => ["=", "i", $data->parent_id],
            // "flag" => ["=", "i", -1],
            // "date" => ["=", "s", $data->mission->today],
            "number" => ["=", "i", $data->mission->mission_id],
        );

        $mission_result = select($line, $mission_col, "EmergencyMission", wheres: $mission_wheres);


        $post = [
            "to" => $line_result[0]["UID"],
            "messages" => [
                [
                    "type" => "text",
                    "text" => "緊急ミッション:「" . $mission_result[0]["mission"] . "」を" . $result[0]["child_name"] . "が達成しました。一言コメントをどうぞ!!",
                ],
            ],
        ];
    }
    $post = json_encode($post);

    $ch = curl_init("https://api.line.me/v2/bot/message/push");
    $options = [
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HEADER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => $post,
    ];
    curl_setopt_array($ch, $options);

    $result = curl_exec($ch);

    $errno = curl_errno($ch);
    if ($errno) {
        return;
    }
}

$response = $data;
echo json_encode($response);
