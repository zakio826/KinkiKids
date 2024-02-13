<?php
// アクセストークン取得
$url = "https://api.line.me/oauth2/v2.1/token";

$postData = array(
    "grant_type" => "authorization_code",
    "code" => $_GET["code"],
    "redirect_uri" => "https://kinkikids.main.jp/kinkikids/line/callback.php", //LINE developersコンソールに設定したURL
    "client_id" => "2001019727",
    "client_secret" => "1249d37b6f32ca5751832a107ad865d3"
);


$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));
curl_setopt($ch, CURLOPT_URL, "https://api.line.me/oauth2/v2.1/token");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$json = json_decode($response);
$accessToken = $json->access_token; //アクセストークンを取得

$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $accessToken));
curl_setopt($ch, CURLOPT_URL, "https://api.line.me/v2/profile");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$json = json_decode($response);


$userInfo = json_decode(json_encode($json), true); //ログインユーザ情報を取得する
$name = $userInfo["displayName"]; //LINEのニックネーム
$form_url = "https://example.com/form?&entry_username=" . $name; //フォームにニックネームを渡す

header("Location: {$form_url}");
