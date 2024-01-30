<?php
//リセットボタンイベント
if (isset($_POST["record_reset"])) :
    unset($_SESSION["r_date"], $_SESSION["r_title"], $_SESSION["r_amount"], $_SESSION["r_type"], $_SESSION["r_spendingcat"], $_SESSION["r_paymentMethod"]);
    header("location: ./index.php");
endif;

if (isset($_SESSION["r_date"]) && isset($_SESSION["r_title"]) && isset($_SESSION["r_amount"]) && isset($_SESSION["r_type"]) && isset($_SESSION["r_spendingCat"]) && isset($_SESSION["r_paymentMethod"])) :
    $r_date = $_SESSION["r_date"];
    if ($_SESSION["r_title"] !== "undefined") :
        $r_title = $_SESSION["r_title"];
    endif;
    $r_amount = $_SESSION["r_amount"];
    $r_type = $_SESSION["r_type"];
    $r_spendingCat = $_SESSION["r_spendingCat"];
    $r_paymentMethod = $_SESSION["r_paymentMethod"];
else :
    // $r_date = "";
    $r_title = "";
    $r_amount = "";
    $r_type = "";
    $r_spendingCat = "";
    $r_paymentMethod = "";
endif;

//月データ検索セッション処理
if (isset($_GET["search_month"])) :
    $search_month = $_GET["search_month"];
    $_SESSION["search_month"] = $search_month;
else :
    $search_month = date("Y-m");
endif;

if (isset($_SESSION["search_month"])) :
    $search_month = $_SESSION["search_month"];
endif;

//カレンダーセッション処理
if (isset($_GET["ym"])) :
    $ym = $_GET["ym"];
    $_SESSION["ym"] = $ym;
else :
    $ym = date("Y-m");
endif;

if (isset($_SESSION["ym"])) :
    $ym = $_SESSION["ym"];
endif;

if (isset($_GET["graph_month"])) :
    $graph_month = $_GET["graph_month"];
    $_SESSION["graph_month"] = $graph_month;
else :
    $graph_month = date("Y-m");
endif;

if (isset($_SESSION["graph_month"])) :
    $graph_month = $_SESSION["graph_month"];
else :
    $graph_month = date("Y-m");
endif;

if (isset($_POST["specific_register"]) && $_POST["specific_register"] === "追加") :
    $specific_register_date = filter_input(INPUT_POST, "specific_register_date", FILTER_SANITIZE_SPECIAL_CHARS);
    $r_date = $specific_register_date;
    $_SESSION["r_date"] = $specific_register_date;
endif;

if (isset($_SESSION["r_date"])) :
    $r_date = $_SESSION["r_date"];
endif;

//絞り込み検索送信処理
if (isset($_POST["detail-search"])) :
    $filtering_title = filter_input(INPUT_POST, "filtering-title", FILTER_SANITIZE_SPECIAL_CHARS);
    $filtering_child = filter_input(INPUT_POST, "filtering-child", FILTER_SANITIZE_NUMBER_INT);
    $filtering_spendingcat = filter_input(INPUT_POST, "filtering-spendingcat", FILTER_SANITIZE_NUMBER_INT);
    $filtering_incomecat = filter_input(INPUT_POST, "filtering-incomecat", FILTER_SANITIZE_NUMBER_INT);
    $filtering_paymentmethod = filter_input(INPUT_POST, "filtering-paymentmethod", FILTER_SANITIZE_NUMBER_INT);
    $filtering_credit = filter_input(INPUT_POST, "filtering-credit", FILTER_SANITIZE_NUMBER_INT);
    $filtering_qr = filter_input(INPUT_POST, "filtering-qr", FILTER_SANITIZE_NUMBER_INT);
    $filtering_type = filter_input(INPUT_POST, "filtering-type", FILTER_SANITIZE_NUMBER_INT);
else :
    $filtering_title = "";
    $filtering_child = "";
    $filtering_spendingcat = "";
    $filtering_incomecat = "";
    $filtering_paymentmethod = "";
    $filtering_credit = "";
    $filtering_qr = "";
    $filtering_type = "";
endif;

if (isset($_POST["detail-search-calendar"])) :
    $calendar_filtering_child = filter_input(INPUT_POST, "calendar-filtering-child", FILTER_SANITIZE_NUMBER_INT);
    $calendar_filtering_spendingcat = filter_input(INPUT_POST, "calendar-filtering-spendingcat", FILTER_SANITIZE_NUMBER_INT);
    $calendar_filtering_incomecat = filter_input(INPUT_POST, "calendar-filtering-incomecat", FILTER_SANITIZE_NUMBER_INT);
else :
    $calendar_filtering_child = "";
    $calendar_filtering_spendingcat = "";
    $calendar_filtering_incomecat = "";
endif;

if ($_SERVER["REQUEST_METHOD"] === "POST") :
    if (isset($_POST["mission_add"]) && $_POST["mission_add"] === "追加") :
        $mission_title = filter_input(INPUT_POST, "mission_title", FILTER_SANITIZE_SPECIAL_CHARS);
        $point = filter_input(INPUT_POST, "point", FILTER_SANITIZE_NUMBER_INT);

        if (!empty($mission_title) && !empty($point)) {
            $sql = "SELECT COUNT(*) FROM help WHERE title = ? AND family_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("si", $mission_title, $family_id);
            sql_check($stmt, $db);

            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count === 0) :
                $exist = "not_exist";
            else :
                $exist = "exist";
            endif;

            if ($exist === "not_exist") {
                $sql = "INSERT INTO help (title, point, family_id) VALUES (?, ?, ?)";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("sii", $mission_title, $point, $family_id);
                sql_check($stmt, $db);
            }
            unset($_POST["mission_add"], $mission_title, $point);
        }
    endif;

    if ($select === "child" && isset($_POST["trade"])) {
        $trade_point = $_POST["trade_point"];
        $trade_money = $_POST["trade_money"];
        $trade_type = $_POST["trade_type"];

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

        $point_data = [
            "points" => ["i", $user["points"] - $trade_point],
        ];

        $point_where = [
            "id" => ["=", "i", $user["id"]],
        ];

        if ($trade_type === "cashing") {
            $point_data["savings"] = ["i", $user["savings"] + $trade_money];
        } else {
            $wish = [
                "savings",
            ];
            $wish_where = [
                "child_id" => ["=", "i", $user["id"]],
            ];

            $wish = select($db, $wish, "wish_list", wheres:$wish_where);

            $wish_update = [
                "savings" => ["i", $wish[0]["savings"] + $trade_money],
            ];

            update($db, $wish_update, "wish_list", wheres: $wish_where);
        }

        update($db, $point_data, "child", wheres:$point_where);

        $messageData = [
            'type' => 'template',
            'altText' => '交換',
            'template' => [
                'type' => 'buttons',
                'title' => $trade_money . '円の交換申請が出ています',
                'text' => '品物名',
                'actions' => [
                    [
                        'type' => 'postback',
                        'label' => '承諾',
                        'data' => 'tok,' . $trade_type . "-" . $trade_point . "-" . $trade_money . "-" . $user["id"],
                    ],
                    [
                        'type' => 'postback',
                        'label' => '拒否',
                        'data' => 'tno'
                    ],
                ]
            ]
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

        unset($_POST["trade"]);
    }

    if ($select === "child" && isset($_POST["exchange"])) {
        $exchange_id = $_POST["exchange"];

        $exchange_col = array(
            "id",
            "name",
            "point",
        );

        $exchange_wheres = array(
            "id" => ["=", "i", $exchange_id],
        );

        $exchange_result = select($db, $exchange_col, "exchange", wheres: $exchange_wheres);

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

        // print_r($exchange_result);
        $point_data = [
            "points" => ["i", $user["points"] - $exchange_result[0]["point"]],
        ];
        $point_where = [
            "id" => ["=", "i", $user["id"]],
        ];

        update($db, $point_data, "child", wheres:$point_where);

        $messageData = [
            'type' => 'template',
            'altText' => '交換',
            'template' => [
                'type' => 'buttons',
                'title' => $exchange_result[0]["name"] . 'の交換申請が出ています',
                'text' => '品物名',
                'actions' => [
                    [
                        'type' => 'postback',
                        'label' => '承諾',
                        'data' => 'ok,' . $exchange_result[0]["id"] . "-" . $user["id"],
                    ],
                    [
                        'type' => 'postback',
                        'label' => '拒否',
                        'data' => 'no'
                    ],
                ]
            ]
        ];


        $sql = "INSERT INTO ChangePoint (id,point,goods,flag) VALUES (" . $user['id'] . ",'" . $exchange_result[0]["point"] . "','" . $exchange_result[0]["name"] . "',1)";
        $stmt = $line->prepare($sql);
        $stmt->execute();

        $post = [
            "to" => $line_result[0]["UID"],
            "messages" => [$messageData],
        ];

        $post = json_encode($post);

        // $ch = curl_init("https://api.line.me/v2/bot/message/push");
        // $options = [
        //     CURLOPT_CUSTOMREQUEST => "POST",
        //     CURLOPT_HEADER => true,
        //     CURLOPT_HTTPHEADER => $headers,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_POSTFIELDS => $post,
        // ];
        // curl_setopt_array($ch, $options);

        // $result = curl_exec($ch);

        // $errno = curl_errno($ch);
        // if ($errno) {
        //     return;
        // }

        unset($_POST["exchange"]);
    }

    if (isset($_POST["exchange_add"]) && $_POST["exchange_add"] === "追加") :
        $exchange_title = filter_input(INPUT_POST, "exchange_title", FILTER_SANITIZE_SPECIAL_CHARS);
        $exchange_point = filter_input(INPUT_POST, "exchange_point", FILTER_SANITIZE_NUMBER_INT);
        $exchange_child = filter_input(INPUT_POST, "exchange_child", FILTER_SANITIZE_NUMBER_INT);

        if (!empty($exchange_title) && !empty($exchange_point)) {
            $sql = "SELECT COUNT(*) FROM exchange WHERE name = ? AND family_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("si", $exchange_title, $family_id);
            sql_check($stmt, $db);

            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            echo $count;
            if ($count === 0) :
                $exist = "ex_not_exist";
            else :
                $exist = "ex_exist";
            endif;

            if ($exist === "ex_not_exist") {
                $sql = "INSERT INTO exchange (name, point, child_id, family_id) VALUES (?, ?, ?, ?)";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("siii", $exchange_title, $exchange_point, $exchange_child, $family_id);
                sql_check($stmt, $db);
            }
            unset($_POST["exchange_add"], $exchange_title, $exchange_point, $exchange_child);
        }
    endif;
endif;

if (isset($_POST["errand_complete"])) {
    $errand_id = filter_input(INPUT_POST, "errand_id", FILTER_SANITIZE_NUMBER_INT);

    $errand_update = [
        "flag" => ["i", 1],
    ];

    $update_wheres = [
        "number" => ["=", "i", $errand_id],
    ];

    update($line, $errand_update, "ErrandMission", $update_wheres);

    $url = $_SERVER["REQUEST_URI"];

    echo $url;
    echo "<br>";

    $url = preg_split("/\?/", $url);

    header("Location: " . $url[0] . "?to_goal");
    exit();
}

if (!strpos($_SERVER["REQUEST_URI"], "review_sheet.php")) {
    if (!isset($_GET["errand_detail"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
        header("Location: ./");
        exit;
    }
}
?>