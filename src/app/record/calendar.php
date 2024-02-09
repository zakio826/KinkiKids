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

<?php  // 収支関連のデータを取得
$user_id = $_SESSION["user_id"];

$columns = array(
    "DATE_FORMAT(income_expense_date,'%Y年%c月')" => "'年月', ",
    "SUM(income_expense_amount)" => "'合計金額'",
);
$column = "";
foreach ($columns as $key => $value) {
    $column .= $key . " AS " . $value;
}

$start_ym = date("Y-m", strtotime("-4 month", $this_date));
$end_ym = date("Y-m", $this_date);

$wheres = array(
    ["user_id", "=", $user_id],
    ["income_expense_date", "BETWEEN", $start_ym."-01", "AND", $end_ym."-".$last_day],
);
$where = "";
for ($i = 0; $i < count($wheres); $i++) {
    for ($j = 0; $j < count($wheres[$i]); $j++) {
        $where .= $wheres[$i][$j];

        if ($j+1 != count($wheres[$i])) {
            $where .= " ";
        } elseif ($i+1 != count($wheres)) {
            $where .= " AND ";
        }
    }
}

$order_by = "'年月'";

$sql = "SELECT ".$column." FROM income_expense WHERE ".$where." ORDER BY ".$order_by.";";
$balance_data =  $db->query($sql);

$income_expense_flag = " AND income_expense_flag = ";

$sql = "SELECT ".$column." FROM income_expense WHERE ".$where.$income_expense_flag."0 ORDER BY ".$order_by.";";
$income_data =  $db->query($sql);

$sql = "SELECT ".$column." FROM income_expense WHERE ".$where.$income_expense_flag."1 ORDER BY ".$order_by.";";
$expense_data =  $db->query($sql);
?>


<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <section class="position-relative">
        <div class="container">
            <div class="row mt-3">
                <div class="position-relative px-5">
                    <table class="w-75 mx-auto" style="caption-side: top;">

                        <!-- 月の変更 -->
                        <caption class="mx-sm-5 text-center">
                            <h5 class="mb-1"><?php echo date("Y", $this_date); ?>年</h5>

                            <form class="h1 row g-0 justify-content-around pt-0" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST" style="color: black;">
                                <label class="col-auto" for="last">＜
                                    <input class="d-none" type="submit" id="last" name="month_transfer" value="<?php echo $last_ym; ?>">
                                </label>

                                <span class="col-4"><?php echo date("n", $this_date); ?>月</span>
                                
                                <label class="col-auto" for="next">＞
                                    <input class="d-none" type="submit" id="next" name="month_transfer" value="<?php echo $next_ym; ?>">
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
            <div class="row mt-5 money-grid">
                <div class="position-relative d-block p-5">
                    <!-- チャートの表示エリア -->
                    <canvas class="w-100 h-100" id="in_exChart"></canvas>
                </div>
            </div>
            
            <!-- 月間収支別カテゴリ詳細グラフ表示 -->
            <div class="row mt-5 money-grid">
                <div class="position-relative d-block p-5">
                    <!-- チャートの表示エリア -->
                    <canvas class="w-100 h-100" id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </section>
</main>


<script>
    <?php
    $dataset = [
        "balance_data" => $balance_data,
        "income_data" => $income_data,
        "expense_data" => $expense_data,
    ];
    foreach($dataset as $name => $data) {
        $data_array = array();
        foreach($data as $index => $item) {
            foreach($item as $key => $value) {
                if (gettype($key) !== "integer") {
                    if ($index == 0) {
                        $data_array += [$key => [$value]];
                    } else {
                        array_push($data_array[$key], $value);
                    }
                }
            }
        }
        echo "const ". $name. " = JSON.parse('". json_encode($data_array, JSON_UNESCAPED_UNICODE). "');";
        echo "console.log(". $name. ");";
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