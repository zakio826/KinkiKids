<!-- カレンダー画面 -->

<!-- ヘッダー -->
<?php
$page_title = "カレンダー";
$stylesheet_name = "calendar.css";
include("../include/header.php");
?>


<?php  // 現在の時間を取得
$date_now = new DateTime("now");
$month_now = true;  // カレンダーが今月であるか

// 月の変更ボタン処理
if (isset($_POST["month_transfer"])) {
    $this_date = strtotime($_POST["month_transfer"]);

    // 変更後の月が今月であるかを判断する
    if ($date_now->format("Y-m") !== $_POST["month_transfer"]) {
        $month_now = false;
    }
} else {
    $this_date = strtotime($date_now->format("Y-m"));
}

$last_ym = date("Y-m", strtotime("-1 month", $this_date));  // 前月取得
$next_ym = date("Y-m", strtotime("+1 month", $this_date));  // 次月取得

$today = intval($date_now->format("d"));  // 今日の日付
$first_week = date("w", strtotime("first day of", $this_date));  // 表示月の初日の曜日
$last_day   = date("d", strtotime("last day of", $this_date));  // 表示月の最終日
?>


<?php  // 大人であれば子どもの情報、子どもであれば、自分の情報を取得
if ($_SESSION["select"] === "child") {
    $user_id = $_SESSION["user_id"];
} else if (isset($_POST["child_select"]) and $_POST["child_select"] != "all") {
    $user_id = $_POST["child_select"];
} else {
    $user_id = null;
}
$familys = $db->query("SELECT user_id, first_name FROM user WHERE family_id = ". $_SESSION["family_id"]. " AND role_id > 30;");
?>


<?php  // 表示月の各記録を日付単位で取得
$calendar_info = array(
    "name" => ["収入記録", "支出記録", "ポイント記録", ],
    "star_color" => ["", "_blue", "_green", "_purple", "_red"],
);
$star_color = ["", "_blue", "_green", "_purple", "_red"];

$monthlyDataset = array(
    "name" => ["dayly_in_data", "dayly_ex_data", "dayly_pt_data", ],
    "query" => [],
    "data" => [],
);

$from = [
    ["income_expense"],
    ["income_expense"],
    ["help_log", "help"],
];

$columns = array(
    "name" => ["日付", "記録内容", "記録情報"],
    "column" => [
        ["income_expense_date", "income_expense_name", "income_expense_amount"],
        ["income_expense_date", "income_expense_name", "income_expense_amount"],
        ["help_log.help_day", "help.help_name", "help.get_point"],
    ],
);

$column = array();
$where = array();

$this_ym = date("Y-m", $this_date);

for ($i = 0; $i < count($monthlyDataset["name"]); $i++) {
    array_push($column, "");
    $column[$i] .= "DATE_FORMAT(". $columns["column"][$i][0]. ",'%e') AS `". $columns["name"][0]. "`";
    $column[$i] .= ", ". $columns["column"][$i][1]. " AS `". $columns["name"][1]. "`";
    $column[$i] .= ", ". $columns["column"][$i][2]. " AS `". $columns["name"][2]. "`";

    array_push($where, "");
    $where[$i] .= "DATE(". $columns["column"][$i][0]. ") BETWEEN '". $this_ym. "-01' AND '". $this_ym. "-". $last_day. "'";
    if ($i <= 1) {
        $where[$i] .= " AND income_expense_flag = ". $i*-1+1;
    }
}

if (is_null($user_id)) {
    array_unshift($where, "family_id = ". $_SESSION["family_id"]);
} else {
    array_unshift($where, "user_id = ". $user_id);
}

for ($i = 0; $i < count($monthlyDataset["name"]); $i++) {
    $sql = "SELECT ". $column[$i]. " FROM ". $from[$i][0];
    if (isset($from[$i][1])) {
        $sql .= " LEFT JOIN ". $from[$i][1]. " ON ". $from[$i][0]. ".". $from[$i][1]. "_id = ". $from[$i][1]. ".". $from[$i][1]. "_id";
        $sql .= " WHERE ". $from[$i][1]. ".";
    } else {
        $sql .= " WHERE ";
    }
    $sql .= $where[0]. " AND ". $where[$i+1];
    $sql .= " ORDER BY ". $columns["column"][$i][0]. " ASC;";

    $stmt = $db->prepare($sql);
    $stmt->execute();
    array_push($monthlyDataset["query"], ($stmt->fetchAll()));

    array_push($monthlyDataset["data"], array());

    if (!empty($monthlyDataset["query"][$i])) {
        foreach ($monthlyDataset["query"][$i] as $index => $item) {
            foreach ($item as $key => $value) {
                if (gettype($key) !== "integer") {
                    if ($key == $columns["name"][0]) {
                        $value = intval($value);
                    }
                    if ($index == 0) {
                        $monthlyDataset["data"][$i] += [$key => [$value]];
                    } else {
                        array_push($monthlyDataset["data"][$i][$key], $value);
                    }
                }
            }
        }
    }
}
?>


<?php  // 表示月のカテゴリ別月間収支データを取得
$categoryDataset = array(
    "name" => ["ex_category_data", "in_category_data", ],
    "query" => [],
    "data" => [],
);

$from_join = array(
    "from" => "income_expense",
    "table" => ["income_expense_category"],
    "column" => [
        ["income_expense_category_id", "income_expense_category_id"],
    ],
);

$from = $from_join["from"];
for ($i = 0; $i < count($from_join["table"]); $i++) {
    $from .= " LEFT JOIN ". $from_join["table"][$i].
        " ON ". $from_join["from"]. ".". $from_join["column"][$i][0].
        " = ". $from_join["table"][$i]. ".". $from_join["column"][$i][1];
}

$columns = array(
    ["カテゴリ名" => $from_join["table"][0].".income_expense_category_name"],
    ["合計金額" => "SUM(".$from_join["from"].".income_expense_amount)"],
);

$column = "";
for ($i = 0; $i < count($columns); $i++) {
    foreach ($columns[$i] as $key => $value) {
        $column .= $value. " AS `". $key. "`";
    }
    if ($i+1 < count($columns)) {
        $column .= ", ";
    }
}

if (is_null($user_id)) {
    $wheres = array(
        $from_join["from"].".family_id = ". $_SESSION["family_id"],
        "DATE(".$from_join["from"].".income_expense_date) BETWEEN '".$this_ym."-01' AND '".$this_ym."-".$last_day."'",
        $from_join["from"].".income_expense_flag =",
    );
} else {
    $wheres = array(
        $from_join["from"].".user_id = ". $user_id,
        "DATE(".$from_join["from"].".income_expense_date) BETWEEN '".$this_ym."-01' AND '".$this_ym."-".$last_day."'",
        $from_join["from"].".income_expense_flag =",
    );
}

for ($i = 0; $i < count($categoryDataset["name"]); $i++) {
    $sql = "SELECT ". $column. " FROM ". $from. " WHERE ";
    for ($j = 0; $j < count($wheres); $j++) {
        $sql .= $wheres[$j];

        if ($j+1 < count($wheres)) {
            $sql .= " AND ";
        } else {
            $sql .= " ";
        }
    }
    $sql .= $i. " GROUP BY `". key($columns[0]). "`";
    $sql .= " ORDER BY `". key($columns[1]). "` DESC;";

    $stmt = $db->prepare($sql);
    $stmt->execute();
    array_push($categoryDataset["query"], ($stmt->fetchAll()));
    array_push($categoryDataset["data"], array());

    foreach ($categoryDataset["query"][$i] as $index => $item) {
        foreach ($item as $key => $value) {
            if (gettype($key) !== "integer") {
                if ($index == 0) {
                    $categoryDataset["data"][$i] += [$key => [$value]];
                } else {
                    array_push($categoryDataset["data"][$i][$key], $value);
                }
            }
        }
    }
}
?>


<?php  // 表示月以前5か月分の月間収支データを取得
$in_exDataset = array(
    "name" => ["expense_data", "income_data", "balance_data"],
    "query" => [],
    "data" => [],
);

$from = "income_expense";

$columns = array(
    ["年月" => "DATE_FORMAT(income_expense_date,'%Y年%c月')"],
    ["合計金額" => "SUM(income_expense_amount)"],
);

$column = "";
for ($i = 0; $i < count($columns); $i++) {
    foreach ($columns[$i] as $key => $value) {
        $column .= $value. " AS `". $key. "`";
    }
    if ($i+1 < count($columns)) {
        $column .= ", ";
    }
}

$start_ym = date("Y-m", strtotime("-4 month", $this_date));
$this_ym = date("Y-m", $this_date);

$wheres = array(
    "DATE(income_expense_date) BETWEEN '".$start_ym."-01' AND '".$this_ym."-".$last_day."'",
    "income_expense_flag =",
);
if (is_null($user_id)) {
    array_unshift($wheres, "family_id = ". $_SESSION["family_id"]);
} else {
    array_unshift($wheres, "user_id = ". $user_id);
}

for ($i = 0; $i+1 < count($in_exDataset["name"]); $i++) {
    $sql = "SELECT ". $column. " FROM ". $from. " WHERE ";
    for ($j = 0; $j < count($wheres); $j++) {
        $sql .= $wheres[$j];

        if ($j+1 < count($wheres)) {
            $sql .= " AND ";
        } else {
            $sql .= " ";
        }
    }
    $sql .= $i. " GROUP BY `". key($columns[0]). "`;";

    $stmt = $db->prepare($sql);
    $stmt->execute();
    array_push($in_exDataset["query"], ($stmt->fetchAll()));
    array_push($in_exDataset["data"], array());

    if ($i != 0) {
        array_push($in_exDataset["data"], $in_exDataset["data"][0]);
    }
    $n = 0;

    foreach ($in_exDataset["query"][$i] as $index => $item) {
        foreach ($item as $key => $value) {
            if (gettype($key) !== "integer") {
                if ($key === key($columns[1])) {
                    if ($i == 0) {
                        $value *= -1;
                    } else {
                        $in_exDataset["data"][2][$key][$n] += $value;
                    }
                }
                if ($index == 0) {
                    $in_exDataset["data"][$i] += [$key => [$value]];
                } else {
                    array_push($in_exDataset["data"][$i][$key], $value);
                }
            }
        }
        $n++;
    }
}
?>


<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <section class="position-relative">
        <div class="container">

            <!-- カレンダー表示 -->
            <div class="row mx-auto mt-3 px-3">
                <div class="position-relative mx-auto px-md-5">
                    <table class="w-100" style="caption-side: top;">
                        <caption class="mx-sm-5 text-center">
                            <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST">
                                
                                <!-- 大人ユーザの場合は子ども切り替え -->
                                <?php if ($_SESSION["select"] === "adult") : ?>
                                    <div class="w-75 mb-3 mx-auto">
                                        <select name="child_select" id="">
                                            <option value="all" <?php if (!isset($user_id)) { echo "selected"; } ?>>子ども全員</option>
                                            <?php foreach ($familys as $family) : ?>
                                                <?php if ($family["user_id"] != $_SESSION["user_id"]) : ?>
                                                    <option value="<?php echo $family["user_id"]; ?>" <?php if (isset($user_id) and $family["user_id"] == $user_id) { echo "selected"; } ?>>
                                                        <?php echo $family["first_name"]; ?>
                                                    </option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>

                                        <button type="submit">変更</button>
                                    </div>
                                <?php endif; ?>

                                <!-- 年表示 -->
                                <h5 class="mb-1"><?php echo date("Y", $this_date); ?>年</h5>
                                
                                <!-- 月表示・変更 -->
                                <input type="hidden" name="month_transfer" value="<?php echo $this_ym; ?>">
                                <h1 class="row g-0 justify-content-around pt-0" style="color: black;">
                                    <label class="col-auto" for="last">＜
                                        <input class="d-none" type="submit" id="last" name="month_transfer" value="<?php echo $last_ym; ?>">
                                    </label>
    
                                    <span class="col-4"><?php echo date("n", $this_date); ?>月</span>
                                    
                                    <label class="col-auto" for="next"><font <?php if ($month_now) { echo 'color="darkgray"'; } ?>>＞</font>
                                        <input class="d-none" type="submit" id="next" name="month_transfer" value="<?php echo $next_ym; ?>" <?php if ($month_now) { echo "disabled"; } ?>>
                                    </label>
                                </h1>
                            </form>
                        </caption>
                        
                        <!-- カレンダー（曜日） -->
                        <thead class="text-center" style="background-color: white;">
                            <tr class="text-center">
                                <th><font color="red">日</font></th>
                                <th>月</th><th>火</th><th>水</th><th>木</th><th>金</th>
                                <th><font color="blue">土</font></th>
                            </tr>
                        </thead>
                        
                        <!-- カレンダー（日付） -->
                        <tbody class="w-100" style="background-color: white;">
                            <form action="../spending/spending_input.php" method="GET">
                                <?php for ($i = 0; $i < 5; $i++) : ?>
                                    <tr>
                                        <?php for ($j = 1; $j <= 7; $j++) : ?>
                                            <?php $day = $i * 7 + $j - $first_week; ?>

                                            <td class="position-relative p-0" style="width: 12.5vw;<?php if ($month_now and $day == $today) { echo ' background-color: lemonchiffon;'; } ?>">
                                                <?php if ($day > 0 and $day <= $last_day) : ?>
                                                    <?php if (!$month_now or $day <= $today) : ?>
                                                        <input class="d-none" type="submit" id="pick_<?php echo $day; ?>" name="pick_date" value="<?php echo date($this_ym. "-". $day); ?>">
                                                    <?php endif; ?>

                                                    <div class="w-100 daily-col" name="week_<?php echo $i; ?>">
                                                        <p class="mb-1 mx-sm-2 pt-1 text-center text-sm-start"><?php echo $day; ?></p>

                                                        <div class="row row-cols-2 row-cols-sm-auto gx-0 gy-1 ps-1 ps-md-2 ps-lg-1 ps-xl-2 ps-xxl-1 align-items-center justify-content-around">
                                                            <?php for ($k = 0; $k < count($monthlyDataset["name"]); $k++) : ?>
                                                                <?php if (isset($monthlyDataset["data"][$k]["日付"]) and in_array($day, $monthlyDataset["data"][$k]["日付"])) : ?>
                                                                    <img class="col daily-star me-1 me-md-2 me-lg-1 me-xl-2 me-xxl-1" src="<?php echo $absolute_path; ?>static/assets/star<?php echo $star_color[$k]; ?>.png">
                                                                <?php endif; ?>
                                                            <?php endfor; ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        <?php endfor; ?>
                                    </tr>
                                    
                                    <tr class="d-none w-100" name="week_info">
                                        <td class="p-2" colspan="7">
                                            <div class="row row-cols-3 g-0 align-items-center justify-content-around w-100 my-2">
                                                <div class="col"><h6 class="w-100 m-0 text-center">収入<span class="d-inline-block">記録</span></h6></div>
                                                <div class="col"><h6 class="w-100 m-0 text-center">支出<span class="d-inline-block">記録</span></h6></div>
                                                <div class="col"><h6 class="w-100 m-0 text-center">お手伝い<span class="d-inline-block">記録</span></h6></div>
                                            </div>

                                            <div class="row row-cols-3 g-0 justify-content-around w-100">
                                                <div class="col info-col" name="in_info"></div>
                                                <div class="col info-col" name="ex_info"></div>
                                                <div class="col info-col" name="pt_info"></div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endfor; ?>
                            </form>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 月間収支表示 -->
            <div class="row row-cols-1 row-cols-md-3 gy-4 gx-0 gx-xl-5 justify-content-around w-100 mx-auto mt-4">
                <?php $in_ex_title = ["もらったお金", "つかったお金", "一か月の合計"]; ?>
                <?php for ($i = 0; $i < count($in_exDataset["name"]); $i++) : ?>
                    <div class="col-8 col-md-3 col-lg-5 col-xl-4 mt-4">
                        <h4 class="row row-cols-md-1 row-cols-lg-auto gy-0 gx-md-2 justify-content-around py-3 py-lg-4 px-xl-2 money-grid">
                            <span class="col-auto my-1 text-center"><?php echo $in_ex_title[$i]; ?></span>
                            <span class="col-auto my-1 text-center">
                                <?php
                                if (!empty($in_exDataset["data"][$i]["合計金額"])) :
                                    switch($i) :
                                        case 0 :
                                            echo end($in_exDataset["data"][1]["合計金額"]);
                                            break;
                                        case 1 :
                                            echo end($in_exDataset["data"][0]["合計金額"])*-1;
                                            break;
                                        default :
                                            echo end($in_exDataset["data"][$i]["合計金額"]);
                                            break;
                                        endswitch;
                                else :
                                    echo 0;
                                endif;
                                ?>円
                            </span>
                        </h4>
                    </div>
                <?php endfor; ?>
            </div>

            
            <!-- 収支別月間カテゴリ詳細グラフ表示 -->
            <div class="mx-3 mt-5 money-grid">
                <div class="row gx-2 justify-content-around mx-3 pt-4">

                    <!-- 収入詳細グラフ -->
                    <div class="position-relative col text-center">
                        <h4 class="position-absolute w-100">支出詳細</h4>
                        <canvas class="w-100 h-auto mt-4 ps-3" id="in_categoryChart"></canvas>
                    </div>

                    <!-- 支出詳細グラフ -->
                    <div class="position-relative col text-center">
                        <h4 class="position-absolute w-100">収入詳細</h4>
                        <canvas class="w-100 h-auto mt-4 ps-3" id="ex_categoryChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- 表示月以前５か月間の収支グラフ表示 -->
            <div class="row mx-3 mt-5 money-grid">
                <div class="position-relative d-block p-4 p-md-5">
                    <canvas class="w-100 h-100" id="in_exChart"></canvas>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- ボトムナビゲーションバー -->
<?php include_once("../include/bottom_nav.php") ?>


<script>
    /*
     * データベースから取得した情報をjson形式で渡す
     */

    <?php
    // 日付単位の記録情報
    echo "const info_data = [";
    for ($i = 0; $i < count($monthlyDataset["name"]); $i++) {
        echo "JSON.parse('". json_encode($monthlyDataset["data"][$i], JSON_UNESCAPED_UNICODE). "'),";
    }
    echo "];";
    // echo "console.log(info_data);";


    // 収支別月間カテゴリ詳細
    for ($i = 0; $i < count($categoryDataset["name"]); $i++) {
        echo "const ". $categoryDataset["name"][$i]. " = JSON.parse('". json_encode($categoryDataset["data"][$i], JSON_UNESCAPED_UNICODE). "');";
    }
    // echo "console.log(". $categoryDataset["name"][0];
    // for ($i = 1; $i < count($categoryDataset["name"]); $i++) {
    //     echo ", ". $categoryDataset["name"][$i];
    // }
    // echo ");";


    // 表示月以前５か月間の収支
    for ($i = 0; $i < count($in_exDataset["name"]); $i++) {
        echo "const ". $in_exDataset["name"][$i]. " = JSON.parse('". json_encode($in_exDataset["data"][$i], JSON_UNESCAPED_UNICODE). "');";
    }
    // echo "console.log(". $in_exDataset["name"][0];
    // for ($i = 1; $i < count($in_exDataset["name"]); $i++) {
    //     echo ", ". $in_exDataset["name"][$i];
    // }
    // echo ");";
    ?>
</script>

<!-- JavaScript -->
<script src="<?php echo $absolute_path; ?>static/js/calendar/calendar.js"></script>
<script src="<?php echo $absolute_path; ?>static/js/calendar/category_chart.js"></script>
<script src="<?php echo $absolute_path; ?>static/js/calendar/in_ex_chart.js"></script>

<!-- フッター -->
<?php include_once("../include/footer.php"); ?>