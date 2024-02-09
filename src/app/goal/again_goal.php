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
// $user_id = $_SESSION["user_id"];

// $columns = array(
//     "DATE_FORMAT(income_expense_date,'%Y年%c月')" => "年月, ",
//     "SUM(income_expense_amount)" => "合計金額",
// );

// $column = "";
// foreach ($columns as $key => $value) {
//     $column .= $key . " AS " . $value;
// }

// $start_ym = date("Y-m", strtotime("-4 month", $this_date));
// $end_ym = date("Y-m", $this_date);

// $wheres = array(
//     ["user_id", "=", $user_id],
//     ["income_expense_flag", "=", 0],
//     ["DATE_FORMAT(income_expense_date,'%Y-%m')", "BETWEEN", $start_ym, "AND", $end_ym],
// );

// $where = "";
// for ($i = 0; $i < count($wheres); $i++) {
//     for ($j = 0; $j < count($wheres[$i]); $j++) {
//         $where .= $wheres[$i];

//         if ($j+1 != count($wheres[$i])) {
//             $where .= " ";
//         } elseif ($i+1 != count($wheres)) {
//             $where .= " AND ";
//         }
//     }
// }

// $order_by = "年月";

// $sql = "SELECT " . $column . " FROM income_expense WHERE " . $where . "ORDER BY " . $order_by;

// $income_data =  $db->query($sql);
// $income_data_json =  json_encode($income_data);

// $wheres[1][2] = 1;
// $where = "";
// for ($i = 0; $i < count($wheres); $i++) {
//     for ($j = 0; $j < count($wheres[$i]); $j++) {
//         $where .= $wheres[$i];

//         if ($j+1 != count($wheres[$i])) {
//             $where .= " ";
//         } elseif ($i+1 != count($wheres)) {
//             $where .= " AND ";
//         }
//     }
// }

// $sql = "SELECT " . $column . " FROM income_expense WHERE " . $where . "ORDER BY " . $order_by;

// $expense_data =  $db->query($sql);
// $expense_data_json =  json_encode($expense_data);

// $income_expense_date
?>


<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <section class="position-relative">
        <div class="container">
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
        </div>
    </section>
</main>


<script>
    // const income_data = JSON.parse('<?php //echo $income_data_json; ?>');
    // const expense_data = JSON.parse('<?php //echo $expense_data_json; ?>');
</script>

<!-- JavaScript -->
<script src="<?php echo $absolute_path; ?>static/js/record_chart5.js"></script>



<!-- フッター -->
<?php include_once("../include/footer.php"); ?>