<section id="review" class="p-section p-section__records-input js-switch-content fade-in" data-tab="tab-3">
    <ul class="review_switch <?php echo $time->format("H:i") < "19:00" ? "daytime" : "night"; ?>" id="review">
        <li class="review_switch__item" data-review="review-1">
            <i class="fa-solid fa-calendar"></i>
            カレンダー
        </li>
        <li class="review_switch__item" data-review="review-2">
            <i class="fa-solid fa-list"></i>
            一覧
        </li>
        <li class="review_switch__item" data-review="review-3">
            <i class="fa-solid fa-chart-pie"></i>
            レポート
        </li>
    </ul>
    <div id="calendar" class="household-calendar switch-review hide" data-review="review-1">
        <?php
        $type = "review";
        include_once("./component/household/calendar.php");
        ?>
    </div>
    <div class="p-section p-section__records-output switch-review fade-in hide" data-review="review-2" id="data-table">
        <h3><?php echo $select === "adult" ? "収支一覧" : "おこづかい帳"; ?></h3>
        <?php
        include_once("./component/review/month-search.php");
        include_once("./component/review/filtering_search.php");
        ?>

        <div class="pc_only">
            <?php
            include_once("./component/review/search-result-pc.php");
            include_once("./component/review/search-result-excel.php");
            ?>
        </div>
        <div class="sp_only">
            <?php
            include_once("./component/review/search-result-sp.php");
            ?>
        </div>
    </div>


    <div id="report" class="p-section p-section__report hide switch-review fade-in" data-review="review-3">
        <?php
        include_once("./component/review/item-pie-chart.php");
        ?>
    </div>
</section>