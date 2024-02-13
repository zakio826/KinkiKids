<!-- カレンダー画面 -->

<!-- ヘッダー -->
<?php
$page_title = "カレンダー";
$stylesheet_name = "calendar.css";
include("../include/header.php");
?>

<?php
// 現在の時間を取得
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
    // $familys = $db->query("SELECT user_id, first_name FROM user WHERE family_id = ". $_SESSION["family_id"]. " AND role_id > 30;");
    // foreach ($familys as $family) {
    //     if ($family["user_id"] != $_SESSION["user_id"]) {
    //         $user_id = $family["user_id"];
    //     }
    // }
}
$familys = $db->query("SELECT user_id, first_name FROM user WHERE family_id = ". $_SESSION["family_id"]. " AND role_id > 30;");
// $user_id = $familys["user_id"];

// エラーが出るのでこれで代用
// $user_id = $_SESSION["user_id"];

$ttt = [];

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

if (is_null($user_id)) {
    $wheres = array(
        "family_id = ". $_SESSION["family_id"],
        "DATE(income_expense_date) BETWEEN '".$start_ym."-01' AND '".$this_ym."-".$last_day."'",
        "income_expense_flag =",
    );
} else {
    $wheres = array(
        "user_id = ". $user_id,
        "DATE(income_expense_date) BETWEEN '".$start_ym."-01' AND '".$this_ym."-".$last_day."'",
        "income_expense_flag =",
    );
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
    $sql .= $i. " GROUP BY `". key($columns[0]). "`;"; array_push($ttt, $sql);
    array_push($in_exDataset["query"], ($db->query($sql)));
    
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
    $sql .= " ORDER BY `". key($columns[1]). "` DESC;"; array_push($ttt, $sql);
    array_push($categoryDataset["query"], ($db->query($sql)));
    
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


<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <section class="position-relative">
        <div class="container">
            <div class="row mt-3">
                <div class="position-relative px-0 px-sm-5">

                    <!-- <?php echo $user_id; ?> -->

                    <!-- <?php echo $_SESSION["family_id"]; ?> -->

                    <!-- 
                        <?php foreach ($ttt as $t) : ?> 
                            <?php echo $t; ?>
                            <br><br>
                        <?php endforeach; ?> 
    
                        <?php if (is_null($user_id)) { echo "true"; } else { echo "false"; } ?> 
    
                        <?php if (is_null($_POST["child_select"])) { echo "true"; } else { echo "false"; } ?> 
    
                        <?php echo gettype($_POST["child_select"]); ?> 
    
                        <?php echo $_POST["child_select"]; ?> 
                    -->
                    
                    <!-- <?php echo var_dump($familys); ?> -->

                    <!-- <?php echo var_dump($in_exDataset["data"][0]); ?> -->
                    <!-- <?php echo var_dump($in_exDataset["data"][0]["合計金額"]); ?> -->
                    


                    <table class="w-75 mx-auto" style="caption-side: top;">
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
                            <?php for ($i = 0; $i < 5; $i++) : ?>
                                <tr>
                                    <?php for ($j = 1; $j <= 7; $j++) : ?>
                                        <?php $day = $i * 7 + $j - $first_week; ?>
                                        <td class="px-1" <?php if ($month_now and $day == $today) { echo 'style="background-color: lemonchiffon;"'; } ?>>
                                            <?php if ($day > 0 and $day <= $last_day) : ?>
                                                <?php echo $day; ?><br>
                                                &nbsp;
                                            <?php else : ?>
                                                &nbsp;<br>
                                                &nbsp;
                                            <?php endif; ?>
                                        </td>
                                    <?php endfor; ?>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 月間収支表示 -->
            <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-around mt-3">
                <h4 class="col-8 col-md-3 row row-cols-auto gy-3 justify-content-around my-3 pb-3 money-grid">
                    <span class="col">つかったお金</span>
                    <span class="col"><?php echo end($in_exDataset["data"][0]["合計金額"]); ?>円</span>
                </h4>

                <h4 class="col-8 col-md-3 row row-cols-auto gy-3 justify-content-around my-3 pb-3 money-grid">
                    <span class="col">もらったお金</span>
                    <span class="col"><?php echo end($in_exDataset["data"][1]["合計金額"]); ?>円</span>
                </h4>

                <h4 class="col-8 col-md-3 row row-cols-auto gy-3 justify-content-around my-3 pb-3 money-grid">
                    <span class="col">１か月合計</span>
                    <span class="col"><?php echo end($in_exDataset["data"][2]["合計金額"]); ?>円</span>
                </h4>
            </div>

            <!-- 収支グラフ表示 -->
            <div class="row mx-3 mt-5 money-grid">
                <div class="position-relative d-block p-5">
                    <!-- チャートの表示エリア -->
                    <canvas class="w-100 h-100" id="in_exChart"></canvas>
                </div>
            </div>
            
            <!-- 月間収支別カテゴリ詳細グラフ表示 -->
            <div class="row mx-3 mt-5 pt-2 money-grid">
                <button class="w-75 mx-auto my-4 btn btn-primary" id="in_exSwitch">収支切り替え</button>
                <div class="position-relative d-block px-5 pb-5">
                    <!-- チャートの表示エリア -->
                    <canvas class="w-100 h-100" id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </section>
</main>


<script>
    <?php
    for ($i = 0; $i < count($in_exDataset["name"]); $i++) {
        echo "const ". $in_exDataset["name"][$i]. " = JSON.parse('". json_encode($in_exDataset["data"][$i], JSON_UNESCAPED_UNICODE). "');";
        echo "console.log(". $in_exDataset["name"][$i]. ");";
    }

    for ($i = 0; $i < count($categoryDataset["name"]); $i++) {
        echo "const ". $categoryDataset["name"][$i]. " = JSON.parse('". json_encode($categoryDataset["data"][$i], JSON_UNESCAPED_UNICODE). "');";
        echo "console.log(". $categoryDataset["name"][$i]. ");";
    }
    ?>
</script>


<!-- JavaScript -->
<script src="<?php echo $absolute_path; ?>static/js/calendar_in_ex_chart.js"></script>
<!-- <script src="<?php echo $absolute_path; ?>static/js/calendar_in_ex_chart_sample.js"></script> -->

<!-- ナビゲーションバー -->
<?php include_once("../include/bottom_nav.php") ?>
<script src="<?php echo $absolute_path; ?>static/js/calendar_category_chart.js"></script>
<!-- <script src="<?php echo $absolute_path; ?>static/js/calendar_category_chart_sample.js"></script> -->

<!-- フッター -->
<?php include_once("../include/footer.php"); ?>