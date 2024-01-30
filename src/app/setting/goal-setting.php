<?php
require_once("./component/common/dbconnect.php");
include_once("./component/common/functions.php");
include_once("./component/common/session.php");
// -- スマホ画面のメニューバーのhtml 
include_once("./component/index/sp-tab2.php");

if (isset($_SESSION["first"]) && $_SESSION["first"] == "first") {
    $_SESSION["first"] = "not_first";
}
// unset($_SESSION["first-login"]);

if ($_SERVER["REQUEST_METHOD"] === "POST") :
    $wish = filter_input(INPUT_POST, "wish", FILTER_SANITIZE_SPECIAL_CHARS);
    $wish_price = filter_input(INPUT_POST, "wish_price", FILTER_SANITIZE_NUMBER_INT);
    $wish_date = filter_input(INPUT_POST, "wish_date", FILTER_SANITIZE_SPECIAL_CHARS);
    $child = filter_input(INPUT_POST, "children", FILTER_SANITIZE_NUMBER_INT);
    $reason = filter_input(INPUT_POST, "wish_reason", FILTER_SANITIZE_NUMBER_INT);
$done = 0;
    $savings = 0;

    // $_SESSION["goal"] = $goal;

    $wish_insert = [
        "family_id" => ["i", $family_id],
        "child_id" => ["i", $child],
        "wish" => ["s", $wish],
"done" => ["i", $done],
        "date" => ["s", $wish_date],
        "price" => ["i", $wish_price],
        "input_date" => ["s", $today->format("Y-m-d")],
"savings" => ["i", $savings],
        "reason" => ["s", $reason],
    ];

    insert($db, $wish_insert, "wish_list");
    unset($_SESSION["goal"], $goal);
endif;

$page_title = "目標設定";
require_once("./component/common/header.php");
?>

<div id="input_data" class="p-section__bank">
    <form method="POST" class="">
        <div>
            <!-- <div>
                <?php
                $col = array(
                    "goal",
                );

                $wheres = array(
                    "child_id" => ["=", "i", $user["id"]],
                );

                $order = array(
                    "order" => ["id", true],
                );
                $result = select($db, $col, "goal", wheres: $wheres, limits: 1, group_order: $order);
                if (count($result) > 0) :
                    while ($row = current($result)) :
                ?>
                        <p>設定された目標</p>
                        <p><?php echo $row["goal"]; ?></p>
                <?php
                        next($result);
                    endwhile;
                endif;
                ?>
            </div> -->
            <div>
                <?php
                $col = array(
                    "wish",
                    "price",
                    "date",
                    "name",
                );
                $wheres = array(
                    "child_id" => ["=", "i", $user["id"]],
                );
                $order = array(
                    "order" => ["wish_list.id", true],
                );
                $join = array(
                    "child" => "wish_list.child_id = child.id",
                );

                $result = select($db, $col, "wish_list", wheres: $wheres, limits: 1, group_order: $order, joins: $join);
                if (count($result) > 0) :
                    while ($row = current($result)) :
                ?>
                       <p>設定された目標</p>
                        <p><?php echo $row["wish"]; ?></p>
                <?php
                        next($result);
                    endwhile;
                endif;
                ?>
            </div>
        </div>
        <?php if ($select === "adult" && count($user["child"]) > 1) : ?>
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
        <?php elseif ($select == "child") : ?>
            <input type="hidden" name="children" value="<?php echo $user["id"]; ?>">
        <?php else : ?>
            <input type="hidden" name="children" value="<?php echo $user["child"][0]["id"]; ?>">
        <?php endif; ?>

        <div>
            <div>
                <p>★買いたいもの</p>
                <input type="text" name="wish" id="wish" maxlength="15" required>
            </div>
        </div>
        <div>
            <div>
                <p>★かかく</p>
                <input type="number" name="wish_price" id="wish_price" maxlength="15" required>
            </div>
        </div>
        <div>
            <div>
                <p>★いつまでに買うの？</p>
                <select name="wish_date" id="" class="c-button--bg-lightblue">
                    <?php
                    $limit = 6;
                    for ($i = 1; $i <= $limit; $i++) : ?>
                        <option value="<?php echo 30 * $i; ?>"><?php echo $i; ?>か月</option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
        <div>
            <div>
                <p>★理由</p>
                <textarea name="wish_reason" id="wish_reason" style="margin-bottom: 10px;"></textarea>
            </div>
        </div>
        <input type="submit" name="goal_setting" value="設定" class="c-button--bg-blue" style="width: 120px;">

    </form>

   

    
</div>
<div style="width: 180px; margin: 50px auto 0; background-color: #ddd; border-radius: 8px; text-align: center; color: #000; font-weight: bold;">
    <a href="./index.php" style="display: block; text-decoration: none; color: #000; line-height: 40px; font-size: 14px;">ホームに戻る</a>
</div>

</body>