<?php
require_once("../component/common/dbconnect.php");
include_once("../component/common/functions.php");

//認証してメール送信が押されたら
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["auth"])) :
    //値受け取り
    $post_username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $post_email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

    //受け取った値と一致するデータがあるかSQLデータ抽出
    $sql = "SELECT username, email FROM user WHERE username=? AND email=? LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ss", $post_username, $post_email);
    sql_check($stmt, $db);
    $stmt->store_result();
    $count = $stmt->num_rows();
    $stmt->bind_result($username, $email);
    $stmt->fetch();

    if ($count === 0) :
        header("Location: ./request-success.php");
        exit();
    //userテーブルに登録されていない情報で送信が行われている場合の処理

    else :
        //トークン作成
        $reset_token = bin2hex(random_bytes(32));

        //リセットリクエストテーブルに記録
        $sql = "INSERT INTO reset_request(username, email, token, time) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $post_time = date("Y-m-d H:i:s");
        $stmt->bind_param("ssss", $post_username, $post_email, $post_time, $reset_token);
        sql_check($stmt, $db);

        //メール作成初期設定
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");

        //送信先
        $to = $post_email;
        //本文に載せるリセットURL(URLは各環境にあわせてください、以下のURLは架空のものです)
        $url = "https://kakeibo.com/password-reset/password-reset.php?token=" . $reset_token;
        //メールタイトル
        $subject =  "【金記キッズ】パスワードリセット用URLのご案内";
        //メール本文("\r\n"で改行)
        $body = "24時間以内に下記URLへアクセスし、パスワードの変更を完了してください。" . "\r\n\r\n" . $url . "\r\n\r\n" . "このメールは送信用です。";

        //メールヘッダー(各環境に合わせてください。以下は架空のものです。)
        // 送信元
        $from = "金記キッズ事務局 <noreply@https://kakeibo.com>";
        // 送信元メールアドレス
        $from_mail = "noreply@https://kakeibo.com";
        // 送信者名
        $from_name = "金記キッズ事務局";
        // 送信者情報の設定
        $header = "";
        $header .= "Content-Type: text/plain \r\n";
        $header .= "Return-Path: " . $from_mail . " \r\n";
        $header .= "From: " . $from . " \r\n";
        $header .= "Sender: " . $from . " \r\n";
        $header .= "Reply-To: " . $from_mail . " \r\n";
        $header .= "Organization: " . $from_name . " \r\n";
        $header .= "X-Sender: " . $from_mail . " \r\n";
        $header .= "X-Priority: 3 \r\n";

        //メール送信処理と送信完了ページへ遷移
        mb_send_mail($to, $subject, $body, $header);
        header("Location: ./request-success.php");
        exit();
    endif;

endif;

$page_title = "パスワード再設定";
$page_headertitle = "パスワード再設定";
include_once("./header.php");
?>
<main class="l-main">
    <?php if (isset($_GET["dataOperation"]) && ($_GET["dataOperation"] === "error" || $_GET["dataOperation"] === "unexpected-error")) : ?> <section class="p-section p-section__full-screen" id="doneOperateBox">
            <div class="p-message-box line-red">
                <p id="doneText">
                    <?php echo $_GET["dataOperation"] === "error" ? "無効なURLです" : "不正な遷移が行われました"; ?>
                </p>
                <button class="c-button c-button--bg-darkred" onclick="onClickOkButton('');">OK</button>
            </div>
        </section>
    <?php endif; ?>

    <section class="p-section p-section__password-reset">
        <form action="" method="post" class="p-form p-form--password-reset">
            <div class="p-form__vertical-input">
                <p>ユーザー名<span>※半角英数字6〜12文字</span></p>
                <input type="text" id="username" name="username" autocomplete="off" minlength="6" maxlength="12" pattern="[0-9a-zA-Z]+$" onkeyup="usernameChange();inputCheck('resetAuth');" required>
                <p class="username-check" id="usernameCheck"></p>
            </div>
            <div class="p-form__vertical-input">
                <p>登録メールアドレス</p>
                <input type="email" name="email" autocomplete="off" id="email" pattern="^[a-zA-Z0-9.!#$&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$" class="js-check" onkeyup="emailChange();inputCheck('resetAuth');" required>
                <p class="email-check" id="emailCheck"></p>
            </div>
            <input id="submitButton" class="c-button c-button--bg-blue" type="submit" name="auth" value="認証してメール送信" disabled>
        </form>

        <a class="c-button c-button--bg-blue" href="../login.php">ログイン画面へ</a>
    </section>
</main>

<?php
$footer_back = "off";
include_once("../component/common/footer.php");
?>

<script src="../js/import.js"></script>
<script src="../js/functions.js"></script>
<script src="../js/input-check.js"></script>
</body>

</html>