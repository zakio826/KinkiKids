<!-- カレンダー画面 -->

<!-- ヘッダー -->
<?php
$page_title = "カレンダー";
include("../include/header.php");
?>

<style>
    thead, tbody, tr, th, td {
        border-collapse: collapse;
        border: 0.5px solid #333;
        /* border-color: black; */
        /* border-style: solid; */
        border-width: unset;
    }
</style>

<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <section class="position-relative">
        <div class="container">
            <div class="row mt-3">
                <div class="position-relative px-5">
                    <table class="w-100" style="caption-side: top;">
                        <!-- 月の変更 -->
                        <caption class="text-center">
                            <h4 class="my-0">
                                <span class="me-1">2024</span>年
                            </h4>
                            <h1 class="pt-0" style="color: black;">
                                <span>＜</span>
                                <span>1</span>月
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
                        </tbody>
                    </table>
                </div>
                <!-- <div class="position-relative px-4">
                </div> -->
            </div>

            <div class="row"></div>
        </div>
    </section>
</main>

<!-- フッター -->
<?php include_once("../include/footer.php"); ?>