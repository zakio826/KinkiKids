<!--取得したデータをセッション変数に保存し、データベースの child テーブルの対応する行の review_date を更新して振り返り日を設定-->

<?php
require_once("./component/common/dbconnect.php");
include_once("./component/common/functions.php");
include_once("./component/common/session.php");

if (isset($_SESSION["first"]) && $_SESSION["first"] == "first") {
    $_SESSION["first"] = "not_first";
}
// unset($_SESSION["first-login"]);

if ($_SERVER["REQUEST_METHOD"] === "POST") :
    $child = filter_input(INPUT_POST, "children", FILTER_SANITIZE_NUMBER_INT);
    $date = filter_input(INPUT_POST, "review-date", FILTER_SANITIZE_SPECIAL_CHARS);

    $_SESSION["date"] = $date;
    $_SESSION["child"] = $child;

    $sql = "UPDATE child SET review_date = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("si", $date, $child);
    sql_check($stmt, $db);

    //不要なデータや変数を削除して、セッションやメモリの管理を効果的に行うためのもの
    unset($_SESSION["date"], $date, $child);
else :
// $_SESSION["goal"] = null;
endif;

$page_title = "振り返り日設定";
require_once("./component/common/header.php");
?>

<div id="input_data" class="p-section__bank">
    <h3>振り返り日を設定してください</h3>
    <form method="POST" class="">
        <div>
            <div>
                <p>子ども選択</p>
                <select name="children" id="">
                    <?php for ($i = 0; $i < count($user["child"]); $i++) : ?>
                        <option value="<?php echo $user["child"][$i]["id"]; ?>"><?php echo $user["child"][$i]["child_name"]; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
        <div>
            <div>
                <p>日付</p>
                <input type="date" name="review-date" id="review-date" value="<?php echo $today->format("Y-m-d") ?>">
            </div>
        </div>
        <input type="submit" name="review_setting" value="設定">
    </form>

    <a href="./index.php">キャンセル</a>
</div>

</body>