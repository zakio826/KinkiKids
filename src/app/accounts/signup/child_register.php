<?php
session_start();
require_once("../component/common/dbconnect.php");
include_once("../component/common/functions.php");

// $family_id = 21;

$exist = "";
$password_match = "";
$samestr = "";

$child_count = 1;
$children = array();
$children[0] = array(
    "name" => "",
    "child_name" => "",
    "sex" => "",
    "age" => "",
    "savings" => "",
    "birthday" => "",
    "password" => "",
);
// print_r($_SESSION["child"]);
// echo "<br>";
// echo $_SESSION["child-count"];
// echo "<br>";
// print_r($children);


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //フォームデータ格納
    if (isset($_POST["type"]) && $_POST["type"] === "child-count") {
        $child_count = filter_input(INPUT_POST, "children", FILTER_SANITIZE_NUMBER_INT);

        for ($i = 0; $i < $child_count; $i++) {
            $children[$i] = array(
                "name" => "",
                "child_name" => "",
                "sex" => "",
                "age" => "",
                "savings" => "",
                "birthday" => "",
                "password" => "",
            );
        }

        $_SESSION["child-count"] = $child_count;
    } elseif (isset($_POST["type"]) && $_POST["type"] === "register") {

        $child_count = $_SESSION["child-count"];

        for ($i = 0; $i < $child_count; $i++) {
            $name = filter_input(INPUT_POST, "name" . $i, FILTER_SANITIZE_SPECIAL_CHARS);
            $child_name = filter_input(INPUT_POST, "child_name" . $i, FILTER_SANITIZE_SPECIAL_CHARS);
            $sex = filter_input(INPUT_POST, "sex" . $i, FILTER_SANITIZE_NUMBER_INT);
            $age = filter_input(INPUT_POST, "age" . $i, FILTER_SANITIZE_NUMBER_INT);
            $savings = filter_input(INPUT_POST, "savings" . $i, FILTER_SANITIZE_NUMBER_INT);
            $birthday = filter_input(INPUT_POST, "birthday" . $i, FILTER_SANITIZE_SPECIAL_CHARS);
            $password = filter_input(INPUT_POST, "childPassword" . $i, FILTER_SANITIZE_SPECIAL_CHARS);
            $password_confirm = filter_input(INPUT_POST, "childPassword_confirm" . $i, FILTER_SANITIZE_SPECIAL_CHARS);

            $children[$i] = array(
                "name" => $name,
                "child_name" => $child_name,
                "sex" => $sex,
                "age" => $age,
                "savings" => $savings,
                "birthday" => $birthday,
                "password" => $password,
            );
        }

        if ($password !== $password_confirm) : //データの数が０なら
            $password_match = "notmatch"; //存在しないことを示す文字列をセット
        else : //データの数が０以外なら
            $password_match = "match"; //データが存在することを示す文字列をセット
        endif;

        if ($child_name === $password) : //データの数が０なら
            $samestr = "same"; //存在しないことを示す文字列をセット
        else : //データの数が０以外なら
            $samestr = "different"; //データが存在することを示す文字列をセット
        endif;
    }
} else {
    //書き直しモードでセッション内容を復元
    if (isset($_GET["mode"]) && $_GET["mode"] === "modify" && isset($_SESSION["child"])) :
        for ($i = 0; $i < count($_SESSION["child"]); $i++) {
            $name = $_SESSION["child"][$i]["name"];
            $child_name = $_SESSION["child"][$i]["child_name"];
            $sex = $_SESSION["child"][$i]["sex"];
            $age = $_SESSION["child"][$i]["age"];
            $savings = $_SESSION["child"][$i]["savings"];
            $birthday = $_SESSION["child"][$i]["birthday"];
            $password = $_SESSION["child"][$i]["password"];

            $children[$i] = array(
                "name" => $name,
                "child_name" => $child_name,
                "sex" => $sex,
                "age" => $age,
                "savings" => $savings,
                "birthday" => $birthday,
                "password" => $password,
            );
        }
    else :
        // unset($_SESSION["child"]);
        $name = "";
        $child_name = "";
        $sex = "";
        $age = "";
        $password = "";
        $birthday = "";
    endif;
}

if ($password_match === "match" && $samestr === "different") :
    for ($i = 0; $i < $child_count; $i++) {
        // $_SESSION["child"][$i] = $children[$i];
        $_SESSION["child"][$i] = array(
            "name" => $children[$i]["name"],
            "child_name" => $children[$i]["child_name"],
            "sex" => $children[$i]["sex"],
            "age" => $children[$i]["age"],
            "savings" => $children[$i]["savings"],
            "birthday" => $children[$i]["birthday"],
            "password" => $children[$i]["password"],
        );
    }

    header("Location: child_confirm.php");
    exit();
endif;



$page_title = "新規ユーザー登録";
include_once("./header.php");
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
                    <p>ユーザー名とパスワードは異なる文字列を入力してください。</p>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <section class="p-section p-section__join-count">
        <form method="POST">
            <input type="hidden" name="type" value="child-count">
            <p>子どもの数：
                <input type="number" name="children" class="js-check" min="1" value="<?php echo $child_count; ?>" onkeyup="childCount();">
                人
            </p>
            <input type="submit" name="child-count" hidden>
        </form>
    </section>

    <section class="p-section p-section__join-input">
        <form class="p-form p-form--join" method="POST">
            <h3><?php echo h($_SESSION["family_name"]); ?>さんの子ども</h3>
            <input type="hidden" name="type" value="register">
            <?php for ($i = 0; $i < $child_count; $i++) : ?>
                <h2>子ども<?php echo $i + 1; ?></h2>
                <div class="p-form__child">
                    <div class="p-form__vertical-input">
                        <p>名前(漢字)<span class="c-text--red">※必須</span><span></span></p>
                        <input type="text" name="child_name<?php echo $i; ?>" autocomplete="off" id="child_name<?php echo $i; ?>" minlength="1" class="js-check" onkeyup="childInputCheck('join');" value="<?php echo h($children[$i]['child_name']); ?>" required>
                    </div>
                    <div class="p-form__vertical-input">
                        <p>名前(カナ)<span class="c-text--red">※必須</span><span>※半角英数字6〜12文字</span></p>
                        <input type="text" name="name<?php echo $i; ?>" autocomplete="off" id="name<?php echo $i; ?>" minlength="1" class="js-check" onkeyup="child_nameChange(<?php echo $i; ?>);childInputCheck('join');" value="<?php echo h($children[$i]['name']); ?>" required>
                        <p class="child_name-check" id="child_nameCheck<?php echo $i; ?>"></p>
                    </div>
                    <div class="p-form__vertical-input">
                        <p>生年月日<span class="c-text--red">※必須</span></p>
                        <input type="date" autocomplete="off" name="birthday<?php echo $i; ?>" id="birthday<?php echo $i; ?>" class="js-check" onchange="birthdayChange(<?php echo $i; ?>);" value="<?php echo h($children[$i]['birthday']) == '' ? date('Y-m-d') : h($children[$i]['birthday']); ?>">
                    </div>
                    <div class="p-form__vertical-input">
                        <p>年齢<span class="c-text--red">※必須</span></p>
                        <label for="age">
                            <input type="number" autocomplete="off" name="age<?php echo $i; ?>" id="age<?php echo $i; ?>" class="js-check" value="<?php echo h($children[$i]["age"]); ?>"> 歳
                            <!-- <input type="hidden" autocomplete="off" name="age<?php echo $i; ?>" id="age<?php echo $i; ?>" class="js-check" value="<?php echo h($children[$i]["age"]); ?>">
                            <?php //echo h($children[$i]["age"]);
                            ?> 歳 -->
                        </label>
                    </div>
                    <div class="p-form__vertical-input">
                        <p>性別<span class="c-text--red">※必須</span></p>
                        <label for="sex">
                            <input type="radio" autocomplete="off" name="sex<?php echo $i; ?>" class="js-check" value="0" <?php echo h($children[$i]["sex"]) == 0 ? "checked" : ""; ?>>男
                            <input type="radio" autocomplete="off" name="sex<?php echo $i; ?>" class="js-check" value="1" <?php echo h($children[$i]["sex"]) == 1 ? "checked" : ""; ?>>女
                        </label>
                    </div>
                    <div class="p-form__vertical-input">
                        <p>お小遣い<span class="c-text--red"></span></p>
                        <label for="savings"><input type="number" autocomplete="off" name="savings<?php echo $i; ?>" id="savings<?php echo $i; ?>" class="js-check" value="<?php echo h($children[$i]['savings']); ?>"> 円</label>
                    </div>
                    <div class="p-form__vertical-input">
                        <p>パスワード<span class="c-text--red">※必須</span><span>※数字4文字</span></p>
                        <input type="password" name="childPassword<?php echo $i; ?>" autocomplete="off" id="childPassword<?php echo $i; ?>" minlength="4" class="js-check" onkeyup="childPassChange('join', <?php echo $i; ?>);childInputCheck('join');" pattern="^[0-9]+$" value="<?php echo h($children[$i]['password']); ?>" required>
                        <p class="pass-check" id="childPassCheck<?php echo $i; ?>"></p>
                    </div>
                    <div class="p-form__vertical-input">
                        <p>確認パスワード<span class="c-text--red">※必須</span></p>
                        <input type="password" autocomplete="off" name="childPassword_confirm<?php echo $i; ?>" minlength="4" class="js-check" onkeyup="childPassConfirmChange(<?php echo $i; ?>);childInputCheck('join');" id="childPasswordConfirm<?php echo $i; ?>" pattern="^[0-9]+$" value="<?php echo h($children[$i]['password']); ?>" required>
                    </div>
                </div>
            <?php endfor; ?>
            <input id="childSubmitButton" name="child-register" class="c-button c-button--bg-blue" type="submit" value="確認画面へ" disabled>
        </form>
    </section>
</main>

<?php
$footer_back = "off";
include_once("../component/common/footer.php");
?>
<script>
    const childUsernameExist = "<?= $exist ?>";
    const childSameStr = "<?= $samestr ?>";
    const childPasswordMatch = "<?= $password_match ?>";
</script>
<script src="../js/child_input_check.js"></script>

</body>

</html>