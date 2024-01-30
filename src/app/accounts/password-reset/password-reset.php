<?php
require_once("../component/common/dbconnect.php");
include_once("../component/common/functions.php");

//メール添付URLからの遷移が正しいかチェック
if (!isset($_GET["token"])) :
    //tokenパラメータがない場合はlogin.phpに戻す
    header("Location: ../login.php?dataOperation=error");
    exit();

//tokenがある場合はリクエスト記録テーブルと照会
else :
    //パラメータについているtokenを変数に格納
    $token = $_GET["token"];
    //パラメータtokenが一致するレコードを抽出
    $sql = "SELECT username, email, time FROM reset_request WHERE token = ? LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $token);
    sql_check($stmt, $db);
    $stmt->store_result();
    $count = $stmt->num_rows();
    $stmt->bind_result($username, $email, $time);
    $stmt->fetch();

    //一致するtokenを持つデータがない場合はlogin.phpに戻す
    if ($count === 0) :
        header("Location: ../login.php?dataOperation=expired");
        exit();

    //有効期限切れの場合はレコードを削除してからindex.phpに戻す
    elseif (date("Y-m-d H:i:s", strtotime("-24 hour")) > $time) :
        $stmt = $db->prepare("DELETE FROM reset_request WHERE token=?");
        $stmt->bind_param("s", $token_param);
        sql_check($stmt, $db);
        header("Location: ./index.php?dataOperation=expired");
        exit();
    endif;

    $stmt->close();

endif;

//再設定ボタンが送信されたら
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["reset"])) :
    $post_username = filter_input(INPUT_POST, "post_username", FILTER_SANITIZE_SPECIAL_CHARS);
    $post_token = filter_input(INPUT_POST, "post_token", FILTER_SANITIZE_SPECIAL_CHARS);
    $post_password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    //送信されたユーザー名とtokenと一致するデータの存在チェック
    $sql = "SELECT username, token FROM reset_request WHERE username=? AND token=? LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ss", $post_username, $post_token);
    sql_check($stmt, $db);
    $stmt->store_result();
    $count = $stmt->num_rows();
    $stmt->fetch();

    //一致するデータが存在する場合
    if ($count === 1) :
        //UPDATE構文で該当ユーザーのパスワードをアップデート（暗号化を忘れない）
        $stmt = $db->prepare("UPDATE user SET password = ? WHERE username=?");
        $hash_password = password_hash($post_password, PASSWORD_DEFAULT);
        $stmt->bind_param("ss", $hash_password, $post_username);
        $success = $stmt->execute();

        if ($success) :
            //パスワード再設定完了で該当tokenを持つレコード削除
            $stmt = $db->prepare("DELETE FROM reset_request WHERE token=?");
            $stmt->bind_param("s", $post_token);
            sql_check($stmt, $db);
            header("Location: ./reset-complete.php");
            exit();

        else :
            //SQLが実行できなかった場合はログイン画面へパラメータ付きで遷移
            header("Location: ../login.php?dataOperation=unexpected-error");
            exit();

        endif;

    //一致するデータが存在しない場合
    else :
        header("Location: ../login.php?dataOperation=unexpected-error");
        exit();
    endif;

endif;

$page_title = "パスワード再設定";
$page_headertitle = "パスワード再設定";
include_once("./header.php");
?>
<main class="l-main">
    <section class="p-section p-section__password-reset">

        <form action="" method="POST" class="p-form p-form--password-reset">
            <input type="hidden" name="post_username" value="<?php echo $username; ?>">
            <input type="hidden" name="post_token" value="<?php echo $token; ?>">
            <div class="p-form__vertical-input">
                <p>パスワード<span>※半角英数字6〜12文字</span></p>
                <input type="password" id="password" name="password" autocomplete="off" minlength="6" maxlength="12" pattern="[0-9a-zA-Z]+$" onkeyup="passChange('reset');inputCheck('reset');" required>
                <p class="pass-check" id="passCheck"></p>
            </div>
            <input id="submitButton" class="c-button c-button--bg-blue" type="submit" name="reset" value="パスワード再設定" disabled>
        </form>

    </section>
</main>

<?php
$footer_back = "off";
include_once("../component/common/footer.php");
?>

<script src="../js/input-check.js"></script>
</body>

</html>