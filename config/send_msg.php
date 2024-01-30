<?php
if (isset($_POST["send"])) {
    $msg = filter_input(INPUT_POST, "msg", FILTER_SANITIZE_SPECIAL_CHARS);

    $msg_col = array(
        "txt" => ["s", $msg],
        "child_id" => ["i", $user["id"]],
        "f_id" => ["i", $family_id],
    );

    insert($line, $msg_col, "LINEtxt");

    $headers = [
        "Authorization: Bearer " . $channelToken,
        "Content-Type: application/json",
    ];


    $line_col = array(
        "UID",
    );

    $line_wheres = array(
        "id" => ["=", "i", $user["parent"]],
    );

    $line_result = select($line, $line_col, "LINEdatabase", wheres: $line_wheres, limits: 1);



    $messageData = [
        "type" => "text",
        "text" => $user["name"] . ": " . $msg,
    ];


    $post = [
        "to" => $line_result[0]["UID"],
        "messages" => [$messageData],
    ];

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
    unset($_POST["send"]);
}
