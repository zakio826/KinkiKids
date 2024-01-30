<!--update 関数を使用して、データベースの "family" テーブルの指定された条件に基づいて "interest" カラムを更新して利子を設定する-->

<?php
require_once("./component/common/dbconnect.php");
include_once("./component/common/functions.php");
include_once("./component/common/session.php");

if (isset($_SESSION["first"]) && $_SESSION["first"] == "first") {
    $_SESSION["first"] = "not_first";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") :
    $interest = filter_input(INPUT_POST, "interest", FILTER_SANITIZE_NUMBER_INT);


    //指定された条件に合致するレコードの "family" テーブルの "interest" カラムが、指定された値に更新
    $interest = array(
        "interest" => ["i", $interest]
    );

    $interest_where = array(
        "id" => ["=", "i", $family_id]
    );

    update($db, $interest, "family", wheres:$interest_where);
endif;

$page_title = "利子設定";
require_once("./component/common/header.php");
?>

<div id="input_data" class="p-section__bank">
    <h3>振り返り日を設定してください</h3>
    <form method="POST" class="">
        <div>
            <div>
                <p>利子</p>
                <input type="number" name="interest" id="interest" style="margin-bottom: 10px;" value="<?php echo $interest; ?>">
            </div>
        </div>
        <input type="submit" name="interest_setting" value="設定" class="c-button--bg-blue" style="width: 120px;" >
    </form>
    <div style="width: 180px; margin: 50px auto 0; background-color: #ddd; border-radius: 8px; text-align: center; color: #000; font-weight: bold;">
    <a href="./index.php" style="display: block; text-decoration: none; color: #000; line-height: 40px; font-size: 14px;">ホームに戻る</a>
</div>



</div>

</body>