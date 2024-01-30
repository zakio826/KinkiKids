<?php
session_start();
require_once("../component/common/dbconnect.php");
include_once("../component/common/functions.php");

if (isset($_SESSION["child"])) :
    $child_count = count($_SESSION["child"]);

    for ($i = 0; $i < $child_count; $i++) {
        if (preg_match("[-]", $_SESSION["child"][$i]["birthday"])) {
            $birth = str_replace("-", "", $_SESSION["child"][$i]["birthday"]);
            $login_id = $_SESSION["child"][$i]["name"] . $birth;
        }
        $children[$i] = array(
            "name" => $_SESSION["child"][$i]["name"],
            "child_name" => $_SESSION["child"][$i]["child_name"],
            "sex" => $_SESSION["child"][$i]["sex"],
            "age" => $_SESSION["child"][$i]["age"],
            "birthday" => $_SESSION["child"][$i]["birthday"],
            "savings" => $_SESSION["child"][$i]["savings"],
            "password" => $_SESSION["child"][$i]["password"],
            "login_id" => $login_id,
        );
    }

    // $parent = 23;
    $parent = $_SESSION["parent_id"];
    $family_id = $_SESSION["family_id"];

    $_SESSION["hasChild"] = true;
else :
    header("Location: index.php");
    exit();
endif;

// print_r($_SESSION["parent_id"]);

//登録ボタン押下でデータを登録
if ($_SERVER["REQUEST_METHOD"] === "POST") :
    $sql = "INSERT INTO child(name, birthday, age, password, parent, sex, child_name, login_id, family_id, first_date, savings) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    for ($i = 0; $i < $child_count; $i++) {
        $date = new DateTime("now");
        $today = $date->format("Y-m-d");

        $encryption = password_hash($children[$i]["password"], PASSWORD_DEFAULT);
        $stmt->bind_param("ssisiissisi", $children[$i]["child_name"], $children[$i]["birthday"], $children[$i]["age"], $encryption, $parent, $children[$i]["sex"], $children[$i]["name"], $children[$i]["login_id"], $family_id, $today, $children[$i]["savings"]);
        sql_check($stmt, $db);

        $child = array (
            "id" => "child_id",
        );

        $child_where = array(
            "login_id" => ["=", "s", $children[$i]["login_id"]],
            "family_id" => ["=", "i", $family_id],
        );

        $child_result = select($db, $child, "child", wheres:$child_where);

        $review_data = array(
            "child_id" => ["i", $child_result[0]["child_id"]],
            "family_id" => ["i", $family_id],
        );

        // insert($db, $review_data, "review");
    }

    unset($_SESSION["child"], $_SESSION["parent"], $_SESSION["child-count"]);

    if (isset($_SESSION["from"]) && $_SESSION["from"] == "account") {
        header("Location: ..account.php");
    } else {
        header("Location: thanks.php");
    }
endif;

$page_title = "登録情報確認";
include_once("./header.php");
?>

<main class="l-main">
    <section class="p-section p-section__join-confirm">
        <form class="p-form p-form--join" action="" method="post">
            <?php for ($i = 0; $i < $child_count; $i++) : ?>
                <div class="p-form__child">
                    <div class="p-form__vertical-input">
                        <p>ログインID</p>
                        <p>【<?php echo h($children[$i]["login_id"]); ?>】</p>
                    </div>
                    <div class="p-form__vertical-input">
                        <p>名前(漢字)</p>
                        <p>【<?php echo h($children[$i]["child_name"]); ?>】</p>
                    </div>
                    <div class="p-form__vertical-input">
                        <p>名前(カナ)</p>
                        <p>【<?php echo h($children[$i]["name"]); ?>】</p>
                    </div>
                    <div class="p-form__vertical-input">
                        <p>生年月日</p>
                        <p>【<?php echo h($children[$i]["birthday"]); ?>】</p>
                    </div>
                    <div class="p-form__vertical-input">
                        <p>性別</p>
                        <p>【<?php echo h($children[$i]["sex"]) == 0 ? "男" : "女"; ?>】</p>
                    </div>
                    <div class="p-form__vertical-input">
                        <p>年齢</p>
                        <p>【<?php echo h($children[$i]["age"]); ?>】</p>
                    </div>
                    <div class="p-form__vertical-input">
                        <p>パスワード</p>
                        <p>【セキュリティ上表示されません】</p>
                    </div>
                </div>
            <?php endfor; ?>
            <div class="u-flex-box">
                <a class="c-button c-button--bg-gray" href="./child_register.php?mode=modify">修正する</a>
                <input class="c-button c-button--bg-blue" type="submit" value="登録する">
            </div>
        </form>
    </section>
</main>

<?php
$footer_back = "off";
include_once("../component/common/footer.php");
?>
</body>

</html>