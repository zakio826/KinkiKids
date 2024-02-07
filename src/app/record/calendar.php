<!-- カレンダー画面 -->

<!-- ヘッダー -->
<?php
$page_title = "カレンダー";
include("../include/header.php");
?>

<?php
$date_now = new DateTime("now");
$month_now = true;

if (isset($_POST["month_transfer"])) {
    $this_date = strtotime($_POST["month_transfer"]);
    if ($date_now->format("Y-m") !== $_POST["month_transfer"]) {
        $month_now = false;
    }
} else {
    $this_date = strtotime($date_now->format("Y-m"));
}

$last_ym = date("Y-m", strtotime("-1 month", $this_date)); //前月取得
$next_ym = date("Y-m", strtotime("+1 month", $this_date)); //次月取得

$today = intval($date_now->format("d"));
$first_week = date("w", strtotime("first day of", $this_date));
$last_day   = date("d", strtotime("last day of", $this_date));
?>

<style>
    thead, tbody, tr, th, td {
        border-collapse: collapse;
        border: 2.5px solid #333;
    }
    .money-grid {
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 6px 8px 0 rgba(0, 0, 0, .5);
    }
</style>

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

            <!-- グラフ表示 -->
            <div class="row mt-5 money-grid">
                <div class="position-relative d-block p-5">
                    <!-- チャートの表示エリア -->
                    <canvas class="w-100 h-100" id="chart"></canvas>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- JavaScript -->
<script src="<?php echo $absolute_path; ?>static/js/record_chart.js"></script>

<!-- フッター -->
<?php include_once("../include/footer.php"); ?>