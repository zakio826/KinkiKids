<?php
    //  オウム返しするだけのphp
    echo "this is reflecting.php page";

    //LINE MessagingAPI アクセストークン
    $accessToken = 'mmKd09WVdDSjKH+JQZijj2xP7SV1lurVGp2esSxafb5RK5eGacDRBfDfTHRF6qIuDC/qC48Orz9i/GMqmA6dAkjlexRkh/s9yUAS0JZsh/Hdsd3FgRwd/TSW/JYoQ3PeSXPJJVZYZWw9Vcv6ZSRoOQdB04t89/1O/w1cDnyilFU=';

    //アクション取得
    $json_string = file_get_contents('php://input');
    $json_object = json_decode($json_string);

    //複数event対応
    for ($i = 0; $i <= count($json_object->{"events"}) - 1; $i++) {
        //アクション判定
        if (strcmp($json_object->{"events"}[$i]->{"type"}, "message") == 0) {
            //個人チャット受信時
            //応答メッセージ
            $replyToken = $json_object->{"events"}[$i]->{"replyToken"};
            $return_message_text  = $json_object->{"events"}[$i]->{"message"}->{"text"};

            //レスポンスフォーマット
            $response_format_text = [
                [
                    "type" => "text",
                    "text" => $return_message_text
                ]
            ];

            //ポストデータ
            $post_data = [
                "replyToken" => $replyToken,
                "messages" => $response_format_text
            ];

            //curl実行
            $curl = curl_init("https://api.line.me/v2/bot/message/reply");
            $header = array(
                'Content-Type: application/json; charser=UTF-8',
                'Authorization: Bearer ' . $accessToken
            );
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($curl, CURLOPT_COOKIEJAR,      'cookie');
            curl_setopt($curl, CURLOPT_COOKIEFILE,     'tmp');
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);

            $result = curl_exec($curl);
            curl_close($curl);
        }
    }
?>