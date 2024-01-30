<!-- 月検索 -->
<div class="p-monthsearch center">
    <?php
    $base_date = strtotime($search_month);
    $prev = date("Y-m", strtotime("-1 month", $base_date));
    $next = date("Y-m", strtotime("+1 month", $base_date));
    $search_link_prev = "?search_month=" . $prev . "#all-data";
    $search_link_next = "?search_month=" . $next . "#all-data";
    $search_link_now = "?search_month=" . date("Y-m") . "#all-data";

    // $search_link_prev = "?ym=" . $prev . "#all-data";
    // $search_link_next = "?ym=" . $next . "#all-data";
    // $search_link_now = "?ym=" . date("Y-m") . "#all-data";
    ?>
    <a href="<?php echo $search_link_prev; ?>">＜</a>
    <input type="month" id="searchMonth" value="<?php echo $search_month; ?>" onchange="onChangeMonth('search_month','searchMonth');">
    <a href="<?php echo $search_link_next; ?>">＞</a>
    <a href="<?php echo $search_link_now; ?>">今月</a>
</div>
<!-- //月検索 -->