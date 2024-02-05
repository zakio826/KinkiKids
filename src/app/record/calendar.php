<!-- カレンダー画面 -->

<!-- ヘッダー -->
<?php
$page_title = "カレンダー";
include("../include/header.php");
?>

<?php
$date_now = new DateTime("now");
$year = $date_now->format("Y");
$month = $date_now->format("m");
$today = $date_now->format("d");

$date = new DateTime($year . $month);
$first_week = $date->modify('first day of')->format("w");
$last_day = $date->modify('last day of')->format("d");
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
                        <caption class="text-center">
                            <h5 class="my-0">
                                <span class="me-1"><?php echo $year; ?></span>年
                            </h5>
                            <h1 class="pt-0" style="color: black;">
                                <span>＜</span>
                                <span><?php echo $date_now->format("n"); ?></span>月
                                <span>＞</span>
                            </h1>
                        </caption>
                        
                        <!-- カレンダー -->
                        <thead class="text-center" style="background-color: white;">
                            <tr class="text-center">
                                <th><font color="red">日</font></th>
                                <th>月</th><th>火</th><th>水</th><th>木</th><th>金</th>
                                <th><font color="blue">土</font></th>
                            </tr>
                        </thead>
                        <tbody class="w-100" style="background-color: white;">
                            <?php for ($i = 0; $i < 29; $i+=7) : ?>
                                <tr>
                                    <?php for ($j = 1; $j <= 7; $j++) : ?>
                                        <td>
                                            <?php
                                            $day = $i + $j - $first_week;
                                            if ($day > 0 and $day <= $last_day) {
                                                echo $day;
                                            } else {
                                                echo "&nbsp;";
                                            }
                                            ?>
                                            <br>
                                            &nbsp;
                                        </td>
                                    <?php endfor; ?>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
                <!-- <div class="position-relative px-4">
                </div> -->
            </div>

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
        </div>
    </section>
</main>

<!-- フッター -->
<?php include_once("../include/footer.php"); ?>