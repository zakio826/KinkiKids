<?php 

//ファイルの読み込み
require($absolute_path."config/db_connect.php");
require($absolute_path."lib/functions.php");

//セッションの開始
session_start();//クラスを使用して接続するように変更

// const DB_NAME = "LAA1579749-kinkikids";
// const HOST = "mysql212.phy.lolipop.lan";
// const USER = "LAA1579749";
// const PASS = "kinkikidsPass";

$accessToken = 'FYTGk0IljqOfhNmsW5KUNWQd2zO/TsxCbkKnDte8Z+O2eEaEkH/e6US3ZC80lv0IMsaDxeRa0eLpOy+GhknGIdd/5u4RXDG/THohgJ1jKuFsh3/U2NRBwPQyi88qeRpV5pnaYIyPLsfOkR6v3gWiPAdB04t89/1O/w1cDnyilFU='; 
$jsonString = file_get_contents('php://input'); 
error_log($jsonString); 
$jsonObj = json_decode($jsonString); 
$message = $jsonObj->{"events"}[0]->{"message"}; 
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};

// 送られてきたメッセージの中身からレスポンスのタイプを選択
if ($message->{"text"} == 'メッセージ確認') {
    // メッセージ送信の場合、MySQLからデータを取得
    $dbConnection = new mysqli(HOST, USER, PASS, DB_NAME);

    // 接続エラーの確認
    if ($dbConnection->connect_error) {
        die("Connection failed: " . $dbConnection->connect_error);
    }

    $query = "SELECT messagetext, FROM line_message";
    $result = $dbConnection->query($query);

    $messageData = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $messageData[] = [
                'type' => 'text',
                'text' => $row['messagetext']
            ];
        }
    } else {
        $messageData[] = [
            'type' => 'text',
            'text' => 'メッセージが見つかりませんでした。'
        ];
    }

    $dbConnection->close();
} else {
    // それ以外は送られてきたテキストをオウム返し
    $messageData[] = ['type' => 'text', 'text' => $message->{"text"}]; 
}

// LINEにレスポンスを送信
$response = ['replyToken' => $replyToken, 'messages' => $messageData]; 
error_log(json_encode($response)); 

$ch = curl_init('https://api.line.me/v2/bot/message/reply'); 
curl_setopt($ch, CURLOPT_POST, true); 
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response)); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json; charser=UTF-8',
    'Authorization: Bearer ' . $accessToken
)); 
$result = curl_exec($ch); 
error_log($result); 
curl_close($ch);
?>
