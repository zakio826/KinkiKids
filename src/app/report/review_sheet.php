<?php
require_once("./component/common/dbconnect.php");
include_once("./component/common/functions.php");
include_once("./component/common/session.php");

$review_check = array(
    // "目標の達成は?" => "answer1",
    // "お手伝いはできたかな？" => "answer2",
    // "お金の使い方" => "answer3",
    // "ポイントの使い方" => "answer4",
    // "とんがりコーンは食べた?" => "answer5",
    "家計簿を正しく入力できたか" => "answer1",
    "振り返りを通して何か学べたか" => "answer2",
);

if (isset($_SESSION["first"]) && $_SESSION["first"] == "first") {
    $_SESSION["first"] = "not_first";
}

if (isset($_SESSION["child_select"])) {
    $child = $_SESSION["child_select"];
} else {
    $child = null;
}

if (isset($_SESSION["review_list"])) {
    $review_list = $_SESSION["review_list"];
} else {
    $review_list = null;
}

// unset($_SESSION["first-login"]);

if (isset($_POST["children"])) {
    $child = filter_input(INPUT_POST, "children", FILTER_SANITIZE_NUMBER_INT);
    $_SESSION["child_select"] = $child;
}

if (isset($_POST["review_list"])) {
    $review_list = $_POST["review_list"];
}

if (isset($_POST["review"])) {
    $review = array();

    // for ($i = 0; $i < count($user["child"]); $i++) {
    //     if ($user["child"][$i]["id"] === $child) {
    foreach ($review_check as $key => $value) {
        $answer = filter_input(INPUT_POST, "review_" . $value, FILTER_SANITIZE_SPECIAL_CHARS);
        if (isset($answer) && $answer !== "") {
            $review[] = $key . ":" . $answer;
        }
    }

    $imp_rev = implode(",", $review);

    $insert_review = array(
        "child_id" => ["i", $child],
        "family_id" => ["i", $family_id],
        "review" => ["s", $imp_rev],
    );
    insert($db, $insert_review, "review");

    $update_data = array(
        "review_flag" => ["i", 1],
    );

    $update_where = array(
        "id" => ["=", "i", $child],
    );

    update($db, $update_data, "child", wheres: $update_where);
    //     }
    // }

    header("Location: ./review_sheet.php");
    exit;
} else {
    $review = array();
}

// print_r($review);
// if ($_SERVER["REQUEST_METHOD"] === "POST") :

// // $_SESSION["child_select"] = $child;

// // $sql = "UPDATE child SET review_date VALUES (?) WHERE child_id = ?";
// // $stmt = $db->prepare($sql);
// // $stmt->bind_param("si", $date, $child);
// // sql_check($stmt, $db);
// // unset($_SESSION["date"], $date, $child);
// else :
// // $_SESSION["goal"] = null;
// endif;

$page_title = "振り返り";
require_once("./component/common/header.php");
?>

<section class="section_review_sheet">
    <?php
    include_once("./component/index/session-param-handler.php");
    include_once("./component/review/month-search.php");
    ?>

    <div id="input_data" class="p-section__review">
        <!-- <a href="./index.php">キャンセル</a> -->

        <form method="POST" class="">
            <div>
                <p>子ども選択</p>
                <select name="children" id="child_select" onchange="submit(this.form)">
                    <option value="-1">子ども全体</option>
                    <?php for ($i = 0; $i < count($user["child"]); $i++) : ?>
                        <option value="<?php echo $user["child"][$i]["id"]; ?>" <?php echo $user["child"][$i]["id"] == $child ? "selected" : ""; ?>>
                            <?php echo $user["child"][$i]["child_name"]; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
        </form>

        <div class="p-togglebutton-box">
            <label for="toggleStyle" class="u-flex-box">
                <span>日付ごとまとめて表示 </span>
                <div>
                    <input type="checkbox" id="toggleStyle" onchange="onChangeListView();">
                    <div class="circle"></div>
                    <div class="button"></div>
                </div>
            </label>
        </div>
        <!-- 収支データ出力 -->
        <div>
            <?php
            $where_month = $search_month . "%";

            //月データ日付まとめで表示
            $date_list = array(); //データがある日付を配列で入れる箱を用意
            $count_list = array(); //各日付されているデータ数を配列で入れる箱を用意
            $week_list = ["日", "月", "火", "水", "木", "金", "土"]; //日本語曜日配列の用意

            $group_data = array(
                "COUNT(*)",
                "date",
            );

            //SQLのWHERE句に対応する条件を構築
            $group_where = array(
                "family_id" => ["=", "i", $family_id],// 家族IDが指定の値と等しい条件
                "date" => ["LIKE", "s", $where_month],// 日付が指定の月である条件
                "records.child_id" => ["!=", "i", 0],// レコードの子供IDが0でない条件
            );

            if ($child > 0) {
                $group_where += array("child_id" => ["=", "i", $child]);
            } else {
                $group_where += array("child_id" => ["<>", "i", 0]);
            }

            $group_order = array(
                "group" => "date",
                "order" => ["date", true],
            );

            $group_result = select($db, $group_data, "records", wheres: $group_where, group_order: $group_order);

            for ($i = 0; $i < count($group_result); $i++) {
                $count_list[] = $group_result[$i]["COUNT(*)"];
                $date_list[] = $group_result[$i]["date"];
            }

            ?>
            <?php

            // 子供の支出と収入の金額を抽出（各子供ごとの合計）
            $sql = "SELECT (SELECT SUM(amount) FROM records WHERE type = 0 AND child_id = ?)AS spending, (SELECT SUM(amount) FROM records WHERE type = 1 AND child_id = ?)AS income FROM records WHERE child_id = ? LIMIT 1";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("iii", $user["child"][0]["id"], $user["child"][0]["id"], $user["child"][0]["id"]);
            sql_check($stmt, $db);
            $stmt->bind_result($spending_amount, $income_amount);
            $sum = 0;
            while ($stmt->fetch()) :
                $sum = $income_amount - $spending_amount;
            endwhile;

            // 月ごとの収入と支出の金額を抽出(家族全体の月ごとの合計）
            $sql = "SELECT (SELECT SUM(amount) FROM records WHERE type = 1 AND family_id = ? AND date LIKE ?) AS income, (SELECT SUM(amount) FROM records WHERE type=0 AND family_id = ? AND date LIKE ?) AS spending FROM records WHERE family_id = ? AND date LIKE ? LIMIT 1";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("isisis", $family_id, $where_month, $family_id, $where_month, $family_id, $where_month);
            sql_check($stmt, $db);
            $stmt->bind_result($income_search, $spending_search);
            $sum_search = 0;
            while ($stmt->fetch()) :
                $sum_search = $income_search - $spending_search;
            endwhile;

            $abs_sum_search = abs($sum_search);
            if ($sum_search >= 0) :
                $sign_search = "";
                $class = "text-blue";
            else :
                $sign_search = "-";
                $class = "text-red";
            endif;

            //貯蓄額の計算
            $all_sum = $savings + $sum;
            $abs_all_sum = abs($all_sum);
            if ($all_sum < 0) :
                $sign = "-";
            else :
                $sign = "";
            endif;
            ?>
            <p class="p-sp-data-box__head">現在の貯蓄額<span class=""><?php echo $sign . "￥" . number_format($abs_all_sum); ?></span></p>
            <!-- //総貯蓄額 -->
            <ul class="p-sp-data-box__item-sum">
                <li>収入<br><span class="text-blue"><?php echo "￥" . number_format($income_search); ?></span></li>
                <li>支出<br><span class="text-red"><?php echo "-￥" . number_format($spending_search); ?></span></li>
                <li>合計<br><span class="<?php echo $class ?>"><?php echo $sign_search . "￥" . number_format($abs_sum_search); ?></span></li>
            </ul>

            <?php if (count($group_result) > 0) : ?>
                <div id="groupView" class="p-sp-data-box__groupview hide">
                    <?php
                    for ($i = 0; $i < count($date_list); $i++) :
                        $search_date = $date_list[$i];
                        $create_week = date("w", strtotime($search_date));
                        $day_of_week = $week_list[$create_week];
                    ?>
                        <div class="p-toggledate-tab js-toggle" id="date<?php echo h($search_date); ?>" onclick="onClickDataBanner('<?php echo $search_date; ?>');">
                            <p class="date">
                                <?php echo date("n月j日", strtotime($date_list[$i])); ?>
                                <span class="day-of-week">(<?php echo ($day_of_week); ?>)</span>
                            </p>
                            <p class="count">(<?php echo h($count_list[$i]); ?>件 )</p>
                        </div>
                        <div class="p-sp-data-box__frame hide" id="item<?php echo $search_date; ?>">
                            <?php
                            $review_data = array(
                                "records.id" => null,
                                "records.date" => null,
                                "records.title" => null,
                                "records.amount" => null,
                                "spending_category.name" => "spending",
                                "income_category.name" => "income",
                                "records.type" => null,
                                "payment_method.name" => "payment",
                                "creditcard.name" => "credit",
                                "qr.name" => "qr",
                                "records.memo" => null,
                                "records.input_time" => null,
                                "child.name" => "child_name",
                            );

                            $review_join = array(
                                "spending_category" => "records.spending_category = spending_category.id",
                                "income_category" => "records.income_category = income_category.id",
                                "payment_method" => "records.payment_method = payment_method.id",
                                "creditcard" => "records.creditcard = creditcard.id",
                                "child" => "records.child_id = child.id",
                                "qr" => "records.qr = qr.id",
                            );

                            $review_where = array(
                                "records.date" => ["LIKE", "s", $search_date],
                                "records.family_id" => ["=", "i", $family_id],
                            );

                            $review_order = array(
                                "order" => ["records.date", true],
                            );

                            $result = select($db, $review_data, "records", joins: $review_join, wheres: $review_where);
                            while ($res = current($result)) :
                            ?>
                                <div class="p-sp-data-box item<?php echo h($res["id"]); ?>">
                                    <div class="u-flex-box p-sp-data-box__overview <?php echo $res["memo"] !== "" ? "hasmemo" : ""; ?>">
                                        <p><?php echo h($res["title"]); ?>
                                            <span>
                                                <?php
                                                if ($res["type"] === 0 && $res["spending"] !== null) {
                                                    echo "(" . h($res["spending"]) . ")";
                                                } else if ($res["type"] === 1 && $res["income"] !== null) {
                                                    echo "(" . h($res["income"]) . ")";
                                                } else {
                                                    echo "(カテゴリー不明)";
                                                }
                                                ?>
                                                <i class="fa-regular fa-message" onclick="showMemo('<?php echo h($res["memo"]); ?>')"></i></span>
                                        </p>
                                        <p class="<?php echo $res["type"] === 0 ? "text-red" : "text-blue" ?>">
                                            <?php echo h($res["type"]) === "0" ? "-¥" . number_format($res["amount"]) : ""; ?>
                                            <?php echo h($res["type"]) === "1" ? "+¥" . number_format($res["amount"]) : ""; ?>
                                        </p>
                                    </div>
                                    <div class="p-sp-data-box__detail">
                                        <p>
                                            <?php
                                            //支払い方法の出力
                                            if ($res["type"] === 0 && $res["payment"] !== null) {
                                                echo "支払い方法：" . h($res["payment"]);
                                            } else if ($res["type"] === 1) {
                                                echo "";
                                            } else {
                                                echo "支払い方法：不明";
                                            }
                                            ?>
                                        </p>

                                        <?php if ($res["payment"] === "クレジット" || $res["payment"] === "スマホ決済") : ?>
                                            <p>
                                                <?php
                                                //クレジット、スマホ決済の詳細出力
                                                if ($res["payment"] === "クレジット") {
                                                    if ($res["credit"] !== null) {
                                                        echo "カード種類：" . h($res["credit"]);
                                                    } else {
                                                        echo "カード種類：不明";
                                                    }
                                                } else if ($res["payment"] === "スマホ決済") {
                                                    if ($res["qr"] !== null) {
                                                        echo "スマホ決済種類：" . h($res["qr"]);
                                                    } else {
                                                        echo "スマホ決済種類：不明";
                                                    }
                                                }
                                                ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <!-- <div class="u-flex-box p-sp-data-box__button">
                                            <form action="./record-edit.php" method="post">
                                                <input type="hidden" name="record_id" value="<?php echo h($res["id"]); ?>">
                                                <input type="submit" class="c-button c-button--bg-green edit" id="" value="編 集">
                                            </form>
                                            <a class="c-button c-button--bg-red delete" id="delete<?php echo h($res["id"]); ?>Group" href="./delete.php?id=<?php echo h($res["id"]); ?>;" onclick="deleteConfirm('<?php echo h($res["title"]); ?>', 'delete<?php echo h($res["id"]); ?>Group');">削 除</a>
                                        </div> -->
                                </div>
                            <?php
                                next($result);
                            endwhile; ?>
                        </div>
                    <?php endfor; ?>
                </div>

                <?php
                $review_data = array(
                    "records.id" => null,
                    "records.date" => null,
                    "records.title" => null,
                    "records.amount" => null,
                    "spending_category.name" => "spending",
                    "income_category.name" => "income",
                    "records.type" => null,
                    "payment_method.name" => "payment",
                    "creditcard.name" => "credit",
                    "qr.name" => "qr",
                    "records.memo" => null,
                    "records.input_time" => null,
                    "child.child_name" => "child_name",
                );

                $review_join = array(
                    "spending_category" => "records.spending_category = spending_category.id",
                    "income_category" => "records.income_category = income_category.id",
                    "payment_method" => "records.payment_method = payment_method.id",
                    "creditcard" => "records.creditcard = creditcard.id",
                    "child" => "records.child_id = child.id",
                    "qr" => "records.qr = qr.id",
                );

                $review_where = array(
                    "records.date" => ["LIKE", "s", $where_month],
                    "records.family_id" => ["=", "i", $family_id],
                );

                if ($child > 0) {
                    $review_where += array("records.child_id" => ["=", "i", $child]);
                } else {
                    $review_where += array("records.child_id" => ["<>", "i", 0]);
                }

                $review_order = array(
                    "order" => ["records.date", true],
                );

                $result = select($db, $review_data, "records", joins: $review_join, wheres: $review_where, group_order: $review_order);
                ?>

                <div id="allView" class="p-sp-data-box__allview">
                    <?php for ($i = 0; $i < count($result); $i++) : ?>
                        <!-- 収支データ出力 -->
                        <div class="p-sp-data-box item<?php echo h($result[$i]["id"]); ?>">
                            <div class="review_box <?php echo $result[$i]["memo"] !== "" ? "hasmemo" : ""; ?>">
                                <p><?php echo $i + 1; ?></p>
                                <p><?php echo h($result[$i]["child_name"]); ?></p>
                            </div>
                            <div class="u-flex-box p-sp-data-box__overview">
                                <p><?php echo h($result[$i]["title"]); ?>
                                    <span>
                                        <?php
                                        if ($result[$i]["type"] === 0 && $result[$i]["spending"] !== null) {
                                            echo "(" . h($result[$i]["spending"]) . ")";
                                        } else if ($result[$i]["type"] === 1 && $result[$i]["income"] !== null) {
                                            echo "(" . h($result[$i]["income"]) . ")";
                                        } else {
                                            echo "(カテゴリー不明)";
                                        }
                                        ?>
                                        <i class="fa-regular fa-message" onclick="showMemo('<?php echo h($result[$i]['memo']); ?>');"></i>
                                    </span>
                                </p>
                                <p class="<?php echo $result[$i]["type"] === 0 ? "text-red" : "text-blue" ?>">
                                    <?php echo h($result[$i]["type"]) === "0" ? "-¥" . number_format($result[$i]["amount"]) : ""; ?>
                                    <?php echo h($result[$i]["type"]) === "1" ? "+¥" . number_format($result[$i]["amount"]) : ""; ?>
                                </p>
                            </div>
                            <!-- <div class="p-sp-data-box__detail">
                                    <p><?php echo date("Y/m/d", strtotime($result[$i]["date"])); ?></p>
                                    <p>
                                        <?php
                                        //支払い方法の出力
                                        if ($result[$i]["type"] === 0 && $result[$i]["payment"] !== null) {
                                            echo "支払い方法：" . h($result[$i]["payment"]);
                                        } else if ($result[$i]["type"] === 1) {
                                            echo "";
                                        } else {
                                            echo "支払い方法：不明";
                                        }
                                        ?>
                                    </p>

                                    <?php if ($result[$i]["payment"] === "クレジット" || $result[$i]["payment"] === "スマホ決済") : ?>
                                        <p>
                                            <?php
                                            //クレジット、スマホ決済の詳細出力
                                            if ($result[$i]["payment"] === "クレジット") {
                                                if ($result[$i]["credit"] !== null) {
                                                    echo "カード種類：" . h($result[$i]["credit"]);
                                                } else {
                                                    echo "カード種類：不明";
                                                }
                                            } else if ($result[$i]["payment"] === "スマホ決済") {
                                                if ($result[$i]["qr"] !== null) {
                                                    echo "スマホ決済種類：" . h($result[$i]["qr"]);
                                                } else {
                                                    echo "スマホ決済種類：不明";
                                                }
                                            }
                                            ?>
                                        </p>
                                    <?php endif; ?>
                                </div> -->
                        </div>
                    <?php endfor; ?>
                    <!-- //収支データ出力 -->
                </div>
            <?php else : ?>
                <div class="p-sp-data-box nodata">
                    <p>データがありません</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="help_list">
            <?php
            $help_data = array(
                "points.id" => "id",
                "date" => "date",
                "child_name" => "child_name",
                "points.point" => "point",
                "title" => "title",
                "COUNT(help_id)" => "count",
            );
            $help_order = array(
                "group" => "title",
                // "order" => ["done", true],
            );
            $help_join = array(
                "help" => "points.help_id = help.id",
                "child" => "points.child_id = child.id",
            );
            ?>
            <div class="this_month">
                <h3>お手伝い</h3>
                <?php
                $help_where = array(
                    "date" => ["LIKE", "s", $where_month],
                );

                if ($child > 0) {
                    $help_where += array("child_id" => ["=", "i", $child]);
                } else {
                    $help_where += array("child_id" => ["<>", "i", 0]);
                }


                $help_list = select($db, $help_data, "points", wheres: $help_where, group_order: $help_order, joins: $help_join);

                while ($help = current($help_list)) :
                    // print_r($help);
                ?>
                    <div class="wish_list-box">
                        <div class="u-flex-box p-sp-data-box__overview">
                            <p>
                                <?php echo h($help["title"]); ?>
                                <span>
                                    (<?php echo $help["point"]; ?>pt)
                                </span>
                            </p>
                            <p class="">
                                <!-- <?php //echo $help["count"]; ?>回 -->

                                <?php echo $help["count"] * $help["point"]; ?>pt
                            </p>
                        </div>
                    </div>
                <?php
                    next($help_list);
                endwhile;
                ?>
            </div>
        </div>

        <div class="wish_review">
            <div class="wish_list">
                <?php
                $wish_data = array(
                    "id",
                    "wish",
                    "child_id",
                    "done",
                    "date",
                    "price",
                );
                $wish_order = array(
                    "order" => ["done", true],
                );
                ?>
                <div class="this_month">
                    <h3>買えなかったものリスト</h3>
                    <?php
                    $wish_where = array(
                        "date" => ["LIKE", "s", $where_month],
                    );

                    if ($child > 0) {
                        $wish_where += array("child_id" => ["=", "i", $child]);
                    } else {
                        $wish_where += array("child_id" => ["<>", "i", 0]);
                    }


                    $wish_list = select($db, $wish_data, "wish_list", wheres: $wish_where, group_order: $wish_order);

                    while ($wish = current($wish_list)) :
                        // print_r($wish);
                    ?>
                        <div class="wish_list-box <?php echo $wish["done"] == 1 ? "done" : "" ?>">
                            <!-- <p><?php echo $wish["id"] ?></p> -->
                            <div class="content">
                                <?php for ($i = 0; $i < count($user["child"]); $i++) : ?>
                                    <?php echo $wish["child_id"] == $user["child"][$i]["id"] ? "<p>" . $user["child"][$i]["child_name"] . "</p>" : "" ?>
                                <?php endfor; ?>
                                <p><?php echo $wish["wish"] ?></p>
                            </div>
                            <p>￥<?php echo $wish["price"] ?></p>
                        </div>
                    <?php
                        echo "<br>";
                        next($wish_list);
                    endwhile;
                    ?>
                </div>

                <div class="next_month">
                    <h3>来月のほしいものリスト</h3>
                    <?php
                    $date = new DateTime($search_month);
                    $next_month = $date->modify("+1 month")->format("Y-m");
                    $wish_where = array(
                        "date" => ["LIKE", "s", $next_month . "%"],
                    );

                    if ($child > 0) {
                        $wish_where += array("child_id" => ["=", "i", $child]);
                    } else {
                        $wish_where += array("child_id" => ["<>", "i", 0]);
                    }


                    $wish_list = select($db, $wish_data, "wish_list", wheres: $wish_where, group_order: $wish_order);

                    while ($wish = current($wish_list)) :
                        // print_r($wish);
                    ?>
                        <div class="wish_list-box <?php echo $wish["done"] == 1 ? "done" : "" ?>">
                            <!-- <p><?php echo $wish["id"] ?></p> -->
                            <div class="content">
                                <?php for ($i = 0; $i < count($user["child"]); $i++) : ?>
                                    <?php echo $wish["child_id"] == $user["child"][$i]["id"] ? "<p>" . $user["child"][$i]["child_name"] . "</p>" : "" ?>
                                <?php endfor; ?>
                                <p><?php echo $wish["wish"] ?></p>
                            </div>
                            <p>￥<?php echo $wish["price"] ?></p>
                        </div>
                    <?php
                        echo "<br>";
                        next($wish_list);
                    endwhile;
                    ?>
                </div>
            </div>

            <div class="review-list">
                <form method="POST">
                    <h3>振り返り</h3>
                    <p>できなかったらチェック</p>
                    <?php foreach ($review_check as $key => $val) : ?>
                        <div class="review-list_box">
                            <p class="list_title">
                                <input type="checkbox" onchange="submit(this.form)" name="review_list[]" value="<?php echo $val; ?>" <?php echo (!is_null($review_list) && in_array($val, $review_list)) ? "checked" : ""; ?>>
                                <?php echo $key; ?>
                            </p>
                            <p class="reason">
                                理由
                                <input type="text" name="review_<?php echo $val ?>" <?php echo (!is_null($review_list) && in_array($val, $review_list)) ? "" : "disabled"; ?>>
                            </p>
                        </div>
                    <?php endforeach; ?>
                    <div class="review-list_box">
                        <p class="list_title">
                            <input type="checkbox" onchange="submit(this.form)" name="review_list[]" value="<?php echo $val; ?>" <?php echo (!is_null($review_list) && in_array($val, $review_list)) ? "checked" : ""; ?>>
                            できた理由・できなかった理由
                        </p>
                        <p class="reason">
                            理由
                            <input type="text" name="review_<?php echo $val ?>">
                        </p>
                    </div>
                    <input type="submit" class="c-button c-button--bg-blue" name="review" value="完了">
                </form>
            </div>

            <!--<div class="review-list">
                <form method="POST">
                    <h3>振り返り</h3>
                    <p>できなかったらチェック</p>
                    <?php
                    foreach ($review_check as $key => $val) : ?>
                        <div class="review-list_box">
                            <p class="list_title">
                                <input type="checkbox" onchange="submit(this.form)" name="review_list[]" value="<?php echo $val; ?>" <?php echo (!is_null($review_list) && in_array($val, $review_list)) ? "checked" : ""; ?>>
                                <?php echo $key; ?>
                            </p>
                            <p class="reason">
                                理由
                                <input type="text" name="review_<?php echo $val ?>" <?php echo (!is_null($review_list) && in_array($val, $review_list)) ? "" : "disabled"; ?>>
                            </p>
                        </div>
                    <?php endforeach; ?>
                    <div class="review-list_box">
                        <p class="list_title">
                            <input type="checkbox" onchange="submit(this.form)" name="review_list[]" value="answer3" <?php echo ""; ?>>
                            できた理由・できなかった理由
                        </p>
                        <p class="reason">
                            理由
                            <input type="text" name="review_answer3">
                        </p>
                    </div>
                    <input type="submit" class="c-button c-button--bg-blue" name="review" value="完了">
                </form>
            </div>-->
        </div>
    </div>
</section>

<?php
//ディレクトリ直下の場合
$footer_back = "on"; //login.php以外に記述
include_once("./component/common/footer.php");
?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="./js/jquery.cookie.js"></script>
<script src="./js/import.js"></script>
<script src="./js/functions.js"></script>

<!-- sp一覧切り替え -->
<script>
    window.onload = function() {
        if ((groupView !== null || allView !== null) && document.cookie.indexOf("dataView=group") !== -1) {
            toggleStyle.checked = true;
            groupView.classList.remove("hide");
            allView.classList.add("hide");
        }
    }
</script>

<script>
    function check(obj) {
        if (obj.checked == true) {

        }
    }
</script>
</body>

</html>