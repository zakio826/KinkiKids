<?php
$callback_url = "//kinkikids.main.jp/KinkiKids/line/callback.php";
$state = rand();

$url = sprintf(
    "https://access.line.me/oauth2/v2.1/authorize"
        . "?response_type=code"
        . "&client_id=%s"
        . "&redirect_uri=%s"
        . "&state=%s"
        . "&scope=profile",
    "2001019727",
    $callback_url,
    $state
);

header("Location: {$url}");
exit;
