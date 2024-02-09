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

<?php  // 表示月以前5か月分の月間収支データを取得
$user_id = $_SESSION["user_id"];

$columns = array(
    ["年月" => "DATE_FORMAT(income_expense_date,'%Y年%c月')"],
    ["合計金額" => "SUM(income_expense_amount)"],
);

$column = "";
for ($i = 0; $i < count($columns); $i++) {
    foreach ($columns[$i] as $key => $value) {
        $column .= $value. " AS `". $key. "`";
        // echo $column. "<br>";
    }
    if ($i+1 < count($columns)) {
        $column .= ", ";
    }
}
// foreach ($columns as $key => $value) {
//     $column .= $value. " AS ". $key;
// }

$start_ym = date("Y-m", strtotime("-4 month", $this_date));
$this_ym = date("Y-m", $this_date);

$wheres = array(
    "user_id = ".$user_id,
    "DATE(income_expense_date) BETWEEN '".$start_ym."-01' AND '".$this_ym."-".$last_day."'",
    "income_expense_flag =",
);

// $group_by = "`年月`";

$where = "";

$dataset = array(
    "name" => ["expense_data", "income_data", "balance_data"],
    "query" => [],
    "data" => [],
    // ["expense_data" => ""],
    // ["income_data" => ""],
    // ["balance_data" => ""],
);

for ($i = 0; $i+1 < count($dataset["name"]); $i++) {
    $sql = "SELECT ".$column." FROM income_expense WHERE ";
    for ($j = 0; $j < count($wheres); $j++) {
        $sql .= $wheres[$j];

        if ($j+1 < count($wheres)) {
            $sql .= " AND ";
        } else {
            $sql .= " ";
        }
    }
    $sql .= $i. " GROUP BY `". key($columns[0]). "`;";
    array_push($dataset["query"], ($db->query($sql)));
    
    array_push($dataset["data"], array());
    if ($i != 0) {
        array_push($dataset["data"], $dataset["data"][0]);
    }

    $n = 0;
    foreach ($dataset["query"][$i] as $index => $item) {
        foreach ($item as $key => $value) {
            if (gettype($key) !== "integer") {
                if ($key === key($columns[1])) {
                    if ($i == 0) {
                        $value *= -1;
                    } else {
                        $dataset["data"][2][$key][$n] += $value;
                    }
                }
                if ($index == 0) {
                    $dataset["data"][$i] += [$key => [$value]];
                } else {
                    array_push($dataset["data"][$i][$key], $value);
                }
            }
        }
        $n++;
    }
}

// array_push($dataset["data"], $dataset["data"][0]);

// for ($i = 0; $i < count($dataset["data"][2]))
// $dataset["data"][2]


// $order_by = "'年月' ASC";

// $sql = "SELECT ".$column." FROM income_expense WHERE ".$where." GROUP BY ".$group_by.";";
// $balance_data =  $db->query($sql);
// $ttt = [$sql];

// $income_expense_flag = " AND income_expense_flag = ";

// $sql = "SELECT ".$column." FROM income_expense WHERE ".$where.$income_expense_flag."0 GROUP BY ".$group_by.";";
// $income_data =  $db->query($sql);
// array_push($ttt, $sql);

// $sql = "SELECT ".$column." FROM income_expense WHERE ".$where.$income_expense_flag."1 GROUP BY ".$group_by.";";
// $expense_data =  $db->query($sql);
// array_push($ttt, $sql);

?>

<?php  // 表示月以前5か月分の月間収支データを取得



?>


<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <section class="position-relative">
        <div class="container">
            <div class="row mt-3">
                <div class="position-relative px-0 px-sm-5">
                    <table class="w-75 mx-auto" style="caption-side: top;">

                        <!-- 月の変更 -->
                        <caption class="mx-sm-5 text-center">
                            <h5 class="mb-1"><?php echo date("Y", $this_date); ?>年</h5>

                            <form class="h1 row g-0 justify-content-around pt-0" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST" style="color: black;">
                                <label class="col-auto" for="last">＜
                                    <input class="d-none" type="submit" id="last" name="month_transfer" value="<?php echo $last_ym; ?>">
                                </label>

                                <span class="col-4"><?php echo date("n", $this_date); ?>月</span>
                                
                                <label class="col-auto" for="next"><font <?php if ($month_now) { echo 'color="darkgray"'; } ?>>＞</font>
                                    <input class="d-none" type="submit" id="next" name="month_transfer" value="<?php echo $next_ym; ?>" <?php if ($month_now) { echo "disabled"; } ?>>
                                </label>
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
                    <span class="col">10000円</span>
                </h4>

                <h4 class="col-8 col-md-3 row row-cols-auto gy-3 justify-content-around my-3 pb-3 money-grid">
                    <span class="col">もらったお金</span>
                    <span class="col">10000円</span>
                </h4>

                <h4 class="col-8 col-md-3 row row-cols-auto gy-3 justify-content-around my-3 pb-3 money-grid">
                    <span class="col">１か月合計</span>
                    <span class="col">10000円</span>
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
            <div class="row mx-3 mt-5 money-grid">
                <div class="position-relative d-block p-5">
                    <!-- チャートの表示エリア -->
                    <canvas class="w-100 h-100" id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </section>
</main>


<script>
    // console.log("<?php //echo $ttt[0] ?>");
    // console.log("<?php //echo $ttt[1] ?>");
    // console.log("<?php //echo $ttt[2] ?>");

    <?php
    // $dataset = [
    //     "balance_data" => $balance_data,
    //     "income_data" => $income_data,
    //     "expense_data" => $expense_data,
    // ];
    // foreach ($dataset as $name => $data) {
    //     $data_array = array();
    //     foreach ($data as $index => $item) {
    //         foreach ($item as $key => $value) {
    //             if (gettype($key) !== "integer") {
    //                 if ($index == 0) {
    //                     $data_array += [$key => [$value]];
    //                 } else {
    //                     array_push($data_array[$key], $value);
    //                 }
    //             }
    //         }
    //     }
    //     echo "const ". $name. " = JSON.parse('". json_encode($data_array, JSON_UNESCAPED_UNICODE). "');";
    //     echo "console.log(". $name. ");";
    // }
    for ($i = 0; $i < count($dataset["name"]); $i++) {
        echo "const ". $dataset["name"][$i]. " = JSON.parse('". json_encode($dataset["data"][$i], JSON_UNESCAPED_UNICODE). "');";
        echo "console.log(". $dataset["name"][$i]. ");";
    }
    ?>
</script>

<!-- JavaScript -->
<script src="<?php echo $absolute_path; ?>static/js/calendar_in_ex_chart.js"></script>
<!-- <script src="<?php echo $absolute_path; ?>static/js/calendar_in_ex_chart_sample.js"></script> -->

<!-- <script src="<?php echo $absolute_path; ?>static/js/calendar_category_chart.js"></script> -->
<script src="<?php echo $absolute_path; ?>static/js/calendar_category_chart_sample.js"></script>

<!-- フッター -->
<?php include_once("../include/footer.php"); ?>