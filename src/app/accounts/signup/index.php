<?php
session_start();
require_once("../component/common/dbconnect.php");
include_once("../component/common/functions.php");

$exist = null;
$password_match = null;
$samestr = null;
$msg = null;

$count = 1;
$parent[0] = array(
    "email" => null,
    "username" => null,
    "family_name" => null,
    "first_name" => null,
    "password" => null,
);

//書き直しモードでセッション内容を復元
// if (isset($_GET["mode"]) && $_GET["mode"] === "modify" && isset($_SESSION["email"], $_SESSION["username"], $_SESSION["password"], $_SESSION["name"]/*, $_SESSION["initial_savings"], $_SESSION["age"]*/)) :
if (isset($_GET["mode"]) && $_GET["mode"] === "modify" && isset($_SESSION["parent"])) :
    // $email = $_SESSION["email"];
    // $username = $_SESSION["username"];
    // $name = $_SESSION["name"];
    // $password = $_SESSION["password"];
    // $age = $_SESSION["age"];
    // $initial_savings = $_SESSION["initial_savings"];

    for ($i = 0; $i < $_SESSION["users"]; $i++) {
        $email = $_SESSION["parent"][$i]["email"];
        $username = $_SESSION["parent"][$i]["username"];
        $family_name = $_SESSION["parent"][$i]["family_name"];
        $first_name = $_SESSION["parent"][$i]["first_name"];
        $password = $_SESSION["parent"][$i]["password"];

        $parent[$i] = array(
            "email" => $email,
            "username" => $username,
            "family_name" => $family_name,
            "first_name" => $first_name,
            "password" => $password,
        );
    }
else :
    for ($i = 0; $i < $count; $i++) {
        $parent[$i] = array(
            "email" => null,
            "username" => null,
            "family_name" => null,
            "first_name" => null,
            "password" => null,
        );
    }

// $email = "";
// $username = "";
// $name = "";
// $password = "";
// $age = "";
// $initial_savings = "";
endif;


if ($_SERVER["REQUEST_METHOD"] === "POST") :
    if (isset($_POST["add_user"])) {
        $msg = "追加ボタンが押されました";
        $_SESSION["users"]++;
        $count = $_SESSION["users"];
        // unset($_POST["add_user"]);

        $parent[$_SESSION["users"] - 1] = array(
            "email" => null,
            "username" => null,
            "family_name" => null,
            "first_name" => null,
            "password" => null,
        );
    } else if (isset($_POST["sub_user"])) {
        $msg = "減らすボタンが押されました";
        if ($_SESSION["users"]) {
        }
        $_SESSION["users"]--;
        $count = $_SESSION["users"];
        // unset($_POST["add_user"]);
    } else if (isset($_POST["register"])) {
        echo $_SESSION["users"] . "<br>";
        // print_r($_SESSION["parent"]);
        //フォームデータ格納
        for ($i = 0; $i < $_SESSION["users"]; $i++) {
            $email = filter_input(INPUT_POST, "email" . $i, FILTER_SANITIZE_EMAIL);
            $username = filter_input(INPUT_POST, "username" . $i, FILTER_SANITIZE_SPECIAL_CHARS);
            $family_name = filter_input(INPUT_POST, "family_name" . $i, FILTER_SANITIZE_SPECIAL_CHARS);
            $first_name = filter_input(INPUT_POST, "first_name" . $i, FILTER_SANITIZE_SPECIAL_CHARS);
            $password = filter_input(INPUT_POST, "password" . $i, FILTER_SANITIZE_SPECIAL_CHARS);
            $password_confirm = filter_input(INPUT_POST, "password_confirm" . $i, FILTER_SANITIZE_SPECIAL_CHARS);

            $parent[$i] = array(
                "email" => $email,
                "username" => $username,
                "family_name" => $family_name,
                "first_name" => $first_name,
                "password" => $password,
            );

            //ユーザーネーム重複確認
            $sql = "SELECT COUNT(*) FROM user WHERE username = ?"; //入力されたユーザー名のデータの数を抽出するSQL文
            $stmt = $db->prepare($sql); //上記SQLをセット
            $stmt->bind_param("s", $username); //？の部分に入力されたユーザー名をセット
            sql_check($stmt, $db);

            $stmt->bind_result($count); //データの数を取得する
            $stmt->fetch();
            $stmt->close();

            if ($count === 0) : //データの数が０なら
                $exist = "notexist"; //存在しないことを示す文字列をセット
            else : //データの数が０以外なら
                $exist = "exist"; //データが存在することを示す文字列をセット
            endif;

            if ($password !== $password_confirm) : //データの数が０なら
                $password_match = "notmatch"; //存在しないことを示す文字列をセット
            else : //データの数が０以外なら
                $password_match = "match"; //データが存在することを示す文字列をセット
            endif;

            if ($username === $password) : //データの数が０なら
                $samestr = "same"; //存在しないことを示す文字列をセット
            else : //データの数が０以外なら
                $samestr = "different"; //データが存在することを示す文字列をセット
            endif;
        }
        echo "<br>";
        print_r($parent);

        // $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        // $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        // $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
        // $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
        // $password_confirm = filter_input(INPUT_POST, "password_confirm", FILTER_SANITIZE_SPECIAL_CHARS);
        // $age = filter_input(INPUT_POST, "age", FILTER_SANITIZE_SPECIAL_CHARS);
        // $initial_savings = filter_input(INPUT_POST, "initial_savings", FILTER_SANITIZE_SPECIAL_CHARS);

        // //ユーザーネーム重複確認
        // $sql = "SELECT COUNT(*) FROM user WHERE username = ?"; //入力されたユーザー名のデータの数を抽出するSQL文
        // $stmt = $db->prepare($sql); //上記SQLをセット
        // $stmt->bind_param("s", $username); //？の部分に入力されたユーザー名をセット
        // sql_check($stmt, $db);

        // $stmt->bind_result($count); //データの数を取得する
        // $stmt->fetch();
        // $stmt->close();

        // if ($count === 0) : //データの数が０なら
        //     $exist = "notexist"; //存在しないことを示す文字列をセット
        // else : //データの数が０以外なら
        //     $exist = "exist"; //データが存在することを示す文字列をセット
        // endif;

        // if ($password !== $password_confirm) : //データの数が０なら
        //     $password_match = "notmatch"; //存在しないことを示す文字列をセット
        // else : //データの数が０以外なら
        //     $password_match = "match"; //データが存在することを示す文字列をセット
        // endif;

        // if ($username === $password) : //データの数が０なら
        //     $samestr = "same"; //存在しないことを示す文字列をセット
        // else : //データの数が０以外なら
        //     $samestr = "different"; //データが存在することを示す文字列をセット
        // endif;

        if ($exist === "notexist" && $password_match === "match" && $samestr === "different") :
            // $_SESSION["email"] = $email;
            // $_SESSION["username"] = $username;
            // $_SESSION["name"] = $name;
            // $_SESSION["password"] = $password;
            // $_SESSION["age"] = $age;
            // $_SESSION["initial_savings"] = $initial_savings;
            for ($i = 0; $i < $_SESSION["users"]; $i++) {
                // $_SESSION["child"][$i] = $children[$i];
                $_SESSION["parent"][$i] = array(
                    "email" => $parent[$i]["email"],
                    "username" => $parent[$i]["username"],
                    "family_name" => $parent[$i]["family_name"],
                    "first_name" => $parent[$i]["first_name"],
                    "password" => $parent[$i]["password"],
                );
            }

            header("Location: confirm.php");
            exit();
        endif;
    }

endif;


$_SESSION["users"] = $count;
$page_title = "新規ユーザー登録";
include_once("./header.php");
echo $msg;
?>

<main class="l-main">
    <?php if ($exist === "exist" || $password_match === "notmatch" || $samestr === "same") : ?>
        <section class="p-section p-section__message p-section__message--join">
            <div class="p-message-box p-message-box--error">

                <?php if ($exist === "exist") : ?>
                    <p>すでに登録されているユーザー名です。</p>
                <?php endif; ?>
                <?php if ($password_match === "notmatch") : ?>
                    <p>パスワードが一致しません。</p>
                <?php endif; ?>
                <?php if ($samestr === "same") : ?>
                    <p><?php print_r($parent); ?></p>
                    <p>ユーザー名とパスワードは異なる文字列を入力してください。</p>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <section class="p-section p-section__join-input">
        <form class="p-form p-form--join" method="POST">
            <?php for ($i = 0; $i < $_SESSION["users"]; $i++) : ?>
                <h3>保護者<?php echo $i + 1; ?></h3>
                <div id="parent<?php echo h($i) ?>">
                    <div class="p-form__vertical-input">
                        <p>メールアドレス<span class="c-text--red">※必須</span></p>
                        <input type="text" name="email<?php echo $i; ?>" autocomplete="off" id="email<?php echo $i; ?>" class="js-check" onkeyup="emailChange(<?php echo $i; ?>);inputCheck('join');" pattern="^[a-zA-Z0-9.!#$&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$" value="<?php echo h($parent[$i]["email"]); ?>" required>
                        <p class="email-check" id="emailCheck<?php echo $i; ?>"></p>
                    </div>
                    <div class="p-form__vertical-input">
                        <p>ユーザー名<span class="c-text--red">※必須</span><span>※半角英数字6〜12文字</span></p>
                        <input type="text" name="username<?php echo $i; ?>" autocomplete="off" id="username<?php echo $i; ?>" minlength="6" maxlength="12" class="js-check" onkeyup="usernameChange(<?php echo $i; ?>);inputCheck('join');" pattern="^[0-9a-zA-Z_]+$" value="<?php echo h($parent[$i]["username"]); ?>" required>
                        <p class="username-check" id="usernameCheck<?php echo $i; ?>"></p>
                    </div>
                    <div class="p-form__vertical-input">
                        <div class="full_name">
                            <span class="name">
                                <p>苗字</p>
                                <input type="text" name="family_name<?php echo $i; ?>" autocomplete="off" id="family_name<?php echo $i; ?>" class="js-check" onkeyup="inputCheck('join');" value="<?php echo h($parent[$i]["family_name"]); ?>" required>
                            </span>
                            <span class="name">
                                <p>名前</p>
                                <input type="text" name="first_name<?php echo $i; ?>" autocomplete="off" id="first_name<?php echo $i; ?>" class="js-check" onkeyup="inputCheck('join');" value="<?php echo h($parent[$i]["first_name"]); ?>" required>
                            </span>
                        </div>
                    </div>
                    <!-- <div class="p-form__vertical-input">
          <p>年齢<span class="c-text--red">※必須</span></p>
          <label><input type="number" autocomplete="off" name="age<?php echo $i; ?>" id="age<?php echo $i; ?>" value="<?php //echo hparent[$i]["($"]age);
                                                                                                                        ?>"> 歳</label>
        </div> -->
                    <div class="p-form__vertical-input">
                        <p>パスワード<span class="c-text--red">※必須</span><span>※半角英数字6〜12文字</span></p>
                        <input type="password" name="password<?php echo $i; ?>" autocomplete="off" id="password<?php echo $i; ?>" minlength="6" maxlength="12" class="js-check" onkeyup="passChange('join', <?php echo $i; ?>);inputCheck('join');" pattern="^[0-9a-zA-Z]+$" value="<?php echo h($parent[$i]["password"]); ?>" required>
                        <p class="pass-check" id="passCheck<?php echo $i; ?>"></p>
                    </div>
                    <div class="p-form__vertical-input">
                        <p>確認パスワード<span class="c-text--red">※必須</span></p>
                        <input type="password" autocomplete="off" name="password_confirm<?php echo $i; ?>" minlength="6" maxlength="12" class="js-check" onkeyup="passConfirmChange(<?php echo $i; ?>);inputCheck('join');" id="passwordConfirm<?php echo $i; ?>" pattern="^[0-9a-zA-Z]+$" value="<?php echo h($parent[$i]["password"]); ?>" required>
                    </div>
                    <!-- <div class="p-form__vertical-input">
                        <p>貯蓄額<span>(任意・変更可)</span></p>
                        <label for="initial_savings" class="u-flex-box">
                            <input type="number" autocomplete="off" name="initial_savings" id="initial_savings<?php echo $i; ?>" value="<?php //echo hparent[$i]["($"]initial_savings);
                                                                                                                                        ?>">
            <span>円</span>
        </label>
    </div> -->
                </div>
            <?php endfor; ?>
            <div class="p-form__vertical-input add_user">
                <!-- <button name="add_user" onclick="">管理ユーザの追加</button> -->
                <input type="submit" name="add_user" value=" + " form="add_user">
                <input type="submit" name="sub_user" value=" - " form="add_user">
            </div>
            <input id="submitButton" class="c-button c-button--bg-blue" name="register" type="submit" value="確認画面へ" disabled>
        </form>

        <form method="post" id="add_user">
        </form>
        <p>ユーザー登録がお済みの方</p>
        <a class="c-button c-button--bg-blue" href="../login.php">ログイン画面へ</a>
    </section>
</main>

<?php
$footer_back = "off";
include_once("../component/common/footer.php");
?>
<script>
    const usernameExist = "<?= $exist ?>";
    const sameStr = "<?= $samestr ?>";
    const passwordMatch = "<?= $password_match ?>";
</script>
<script src="../js/input-check.js"></script>

</body>

</html>