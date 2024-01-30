<!-- <section class="p-section p-section__calendar js-switch-content fade-in"> -->
<?php
//パラメータ処理
$base_date = strtotime($ym); //パラメータもしくは現在の年月のタイムスタンプ
$prev = date("Y-m", strtotime("-1 month", $base_date)); //前月取得
$next = date("Y-m", strtotime("+1 month", $base_date)); //次月取得
$calendar_title = date("Y-m", $base_date); //カレンダータイトル

$max_view = 10;

if (!isset($_GET["page_id"])) :
    $page_id = 1;
    $page_param = $page_id - 1;
    $calendar_link_prev = "ym=" . $prev;
    $calendar_link_next = "ym=" . $next;
    $calendar_link_now = "ym=" . date("Y-m");
else :
    $page_id = $_GET["page_id"];
    $page_param = ($page_id - 1) * $max_view;
    $calendar_link_prev = "ym=" . $prev . "&page_id=" . $page_id;
    $calendar_link_next = "ym=" . $next . "&page_id=" . $page_id;
    $calendar_link_now = "ym=" . date("Y-m") . "&page_id=" . $page_id;
endif;

//セル内HTML変数格納
// $cel_spending = "<span class='text-red'>-¥" . number_format($spending_sum) . "</span>";
// $cel_income = "<span class='text-blue'>¥" . number_format($income_sum) . "</span>";
$close_a = "</a>";

$star = "<img class='mark top left' src='./img/star.png'>";
$star_green = "<img class='mark top right' src='./img/star_green.png'>";
$star_blue = "<img class='mark bottom left' src='./img/star_blue.png'>";
$star_red = "<img class='mark bottom right' src='./img/star_red.png'>";
$star_purple = "<img class='mark bottom right' src='./img/star_purple.png'>";
?>
<div class=" p-monthsearch">
    <a href="index.php?<?php echo $calendar_link_prev ?>">＜</a>
    <input type="month" id="calendarMonth<?php echo $type; ?>" value="<?php echo $calendar_title; ?>"
        onchange="onChangeMonth('ym', 'calendarMonth<?php echo $type; ?>');">
    <a href="index.php?<?php echo $calendar_link_next ?>">＞</a>
    <a href="index.php?<?php echo $calendar_link_now ?>">今月</a>
</div>

<?php if ($select === "adult") : ?>
    <?php include("filtering_search_calendar.php"); ?>

    <table class="p-calendar">
        <tr>
            <th>日</th>
            <th>月</th>
            <th>火</th>
            <th>水</th>
            <th>木</th>
            <th>金</th>
            <th>土</th>
        </tr>
        <?php
        //表示中の月の日数を取得
        $day_count = date("t", $base_date);

        //曜日取得（日曜日なら0、月曜日なら1・・・土曜日なら6が入る）
        $youbi = date("w", $base_date);

        //カレンダー配列の初期化
        $weeks = [];
        $week = "";

        //初週の空セルの作成（$youbiに格納されている数だけ空のtdタグを$weekに追加）
        $week .= str_repeat("<td></td>", $youbi);

        //日毎のデータ抽出(1日から表示月の末尾まで繰り返す)
        for ($day = 1; $day <= $day_count; $day++, $youbi++) {

            //YYYY-mm-dd形式の文字列生成（セルの日付とリンクパラメータ、SQLのbind_paramに使用）
            if ($day < 10) :
                $date = $ym . "-" . "0" . $day;
            else :
                $date = $ym . "-" . $day;
            endif;

            //詳細表示のリンク生成
            if (isset($_GET["ym"]) || isset($_GET["page_id"]) || isset($_GET["search_month"])) :
                $detail_url = $_SERVER["REQUEST_URI"] . "&detail=" . $date;
            else :
                $detail_url = $_SERVER["REQUEST_URI"] . "?detail=" . $date;
            endif;

            //データ抽出
            $sql = "SELECT (SELECT SUM(amount) FROM records WHERE type=0 AND family_id=? AND date=?)AS spending_sum, (SELECT SUM(amount) FROM records WHERE type=1 AND family_id=? AND date=?)AS income_sum FROM records WHERE family_id=? AND date=?";
            $limit = " LIMIT 1";
            if (isset($_POST["detail-search-calendar"])) :
                //選択項目のカラム配列
                $filter_column = [
                    "records.child_id", "records.spending_category", "records.income_category"
                ];
                //選択項目の値配列
                $filter_value = [$calendar_filtering_child, $calendar_filtering_spendingcat, $calendar_filtering_incomecat];

                //繰り返し構文で上記2つの配列を使用しながら、選択項目のWHERE分を作成し追加する
                for ($i = 0; $i < count($filter_column); $i++) :
                    //各選択項目のWHERE文作成
                    $add_sql = add_sql_item($filter_column[$i], $filter_value[$i]);
                    $sql .= $add_sql;
                endfor;
            endif;

            $debt_col = [
                "date",
                "repayment",
            ];

            $debt_where = [
                "family_id" => ["=", "i", $family_id],
                "date" => ["=", "s", $date],
                "repayment" => ["=", "s", $date],
            ];

            $debt_result = select($db, $debt_col, "debt", wheres: $debt_where);

            $help_col = [
                "date",
            ];

            $help_where = [
                "family_id" => ["=", "i", $family_id],
                "date" => ["=", "s", $date],
            ];

            $help_result = select($db, $help_col, "points", wheres: $help_where);

            $stmt = $db->prepare($sql . $limit);
            $stmt->bind_param("isisis", $family_id, $date, $family_id, $date, $family_id, $date);
            $stmt->execute();
            $stmt->bind_result($spending_sum, $income_sum);
            $stmt->fetch();

            $cel_link = "<a class='mark_position' href='" . $detail_url . "'>";

            //日付セルの中身生成
            $cel_content = $cel_link;
            if ($spending_sum > 0 || $income_sum > 0) {
                $cel_content .= $star;
            }

            if (count($debt_result) > 0 && $debt_result[0]["date"] == $date) {
                $cel_content .= $star_red;
            }

            if (count($debt_result) > 0 && $debt_result[0]["repayment"] == $date) {
                $cel_content .= $star_blue;
            }

            if (count($help_result) > 0) {
                $cel_content .= $star_green;
            }
            //どちらも存在しないとき
            $cel_content .= $close_a;

            //$weekにセルを追加
            $week .= "<td>" . $day . "<br>" . $cel_content . "</td>";

            //抽出結果の初期化
            $spending_sum = null;
            $income_sum = null;
            $stmt->close();

            //週末、月末の処理
            if ($youbi % 7 == 6 || $day == $day_count) :
                $weeks[] = "<tr>" . $week . "</tr>";
                $week = "";
            endif;
        }

        // 出力
        foreach ($weeks as $week) {
            echo $week;
        }
        ?>
    </table>
<?php endif; ?>

<?php if ($select === "child") : ?>
    <table class="p-calendar">
        <tr>
            <th>日</th>
            <th>月</th>
            <th>火</th>
            <th>水</th>
            <th>木</th>
            <th>金</th>
            <th>土</th>
        </tr>
        <?php
        //表示中の月の日数を取得
        $day_count = date("t", $base_date);

        //曜日取得（日曜日なら0、月曜日なら1・・・土曜日なら6が入る）
        $youbi = date("w", $base_date);

        //カレンダー配列の初期化
        $weeks = [];
        $week = "";

        //初週の空セルの作成（$youbiに格納されている数だけ空のtdタグを$weekに追加）
        $week .= str_repeat("<td></td>", $youbi);

        //日毎のデータ抽出(1日から表示月の末尾まで繰り返す)
        for ($day = 1; $day <= $day_count; $day++, $youbi++) {

            //YYYY-mm-dd形式の文字列生成（セルの日付とリンクパラメータ、SQLのbind_paramに使用）
            if ($day < 10) :
                $date = $ym . "-" . "0" . $day;
            else :
                $date = $ym . "-" . $day;
            endif;

            //詳細表示のリンク生成
            if (isset($_GET["ym"]) || isset($_GET["page_id"]) || isset($_GET["search_month"])) :
                $detail_url = $_SERVER["REQUEST_URI"] . "&detail=" . $date;
            else :
                $detail_url = $_SERVER["REQUEST_URI"] . "?detail=" . $date;
            endif;

            $debt_col = [
                "date",
            ];

            $debt_where = [
                "child_id" => ["=", "i", $user["id"]],
                "date" => ["=", "s", $date],
            ];

            $debt_result = select($db, $debt_col, "debt", wheres: $debt_where);

            $repayment_col = [
                "repayment",
                "division",
            ];

            $to = new DateTime();
            $repayment_month = $to->modify("+1 month")->format("Y-m");
            $repayment_where = [
                "child_id" => ["=", "i", $user["id"]],
                "repayment" => ["LIKE", "s", $repayment_month . "%"],
            ];

            $repayment_result = select($db, $repayment_col, "debt", wheres: $repayment_where);

            $help_col = [
                "date",
            ];

            $help_where = [
                "child_id" => ["=", "i", $user["id"]],
                "date" => ["=", "s", $date],
            ];

            $help_result = select($db, $help_col, "points", wheres: $help_where);

            $trade_col = [
                "date",
                "income_category",
            ];

            $trade_where = [
                "child_id" => ["=", "i", $user["id"]],
                "date" => ["=", "s", $date],
                "income_category" => ["=", "i", -1],
            ];

            $trade_result = select($db, $trade_col, "records", wheres: $trade_where);

            //データ抽出
            $sql = "SELECT (SELECT SUM(amount) FROM records WHERE type=0 AND child_id=? AND date=?)AS spending_sum, (SELECT SUM(amount) FROM records WHERE type=1 AND child_id=? AND date=?)AS income_sum FROM records WHERE child_id=? AND date=? LIMIT 1";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("isisis", $user["id"], $date, $user["id"], $date, $user["id"], $date);
            $stmt->execute();
            $stmt->bind_result($spending_sum, $income_sum);
            $stmt->fetch();

            $cel_link = "<a class='mark_position' href='" . $detail_url . "'>";

            //日付セルの中身生成
            $cel_content = $cel_link;
            if ($spending_sum > 0 || $income_sum > 0) {
                $cel_content .= $star;
            }

            if (count($debt_result) > 0) {
                $cel_content .= $star_red;
            }

            if (count($trade_result) > 0) {
                $cel_content .= $star_blue;
            }

            // echo "<br>" . $this_month;
            if (isset($repayment_result[0]["repayment"])) {
                $repayment = new DateTime($repayment_result[0]["repayment"]);
                // echo  ":" . $repayment->format("Y-m-d");
                for ($i = 0; $i < $repayment_result[0]["division"]; $i++) {
                    if (count($repayment_result) > 0 && $date == $repayment->format("Y-m-d")) {
                        $cel_content .= $star_purple;
                    }
                    $repayment = $repayment->modify("+1 month");
                }
            }

            if (count($help_result) > 0) {
                $cel_content .= $star_green;
            }
            //どちらも存在しないとき
            $cel_content .= $close_a;

            //$weekにセルを追加
            $week .= "<td>" . $day . "<br>" . $cel_content . "</td>";

            //抽出結果の初期化
            $spending_sum = null;
            $income_sum = null;
            $stmt->close();

            //週末、月末の処理
            if ($youbi % 7 == 6 || $day == $day_count) :
                $weeks[] = "<tr>" . $week . "</tr>";
                $week = "";
            endif;
        }

        // 出力
        foreach ($weeks as $week) {
            echo $week;
        }
        ?>
    </table>
<?php endif; ?>

<div class="p-calendar__sum" style="background-color: rgba(255, 255, 255, 0.8); padding: 10px 0">
    <?php
    if ($select === "adult") {
        $sql = "SELECT (SELECT SUM(amount) FROM records WHERE type = 0 AND date LIKE ? AND family_id = ?)AS spending_sum, (SELECT SUM(amount) FROM records WHERE type = 1 AND date LIKE ? AND family_id = ?)AS income_sum FROM records WHERE family_id = ? LIMIT 1";
    } else if ($select === "child") {
        $sql = "SELECT (SELECT SUM(amount) FROM records WHERE type = 0 AND date LIKE ? AND child_id = ?)AS spending_sum, (SELECT SUM(amount) FROM records WHERE type = 1 AND date LIKE ? AND child_id = ?)AS income_sum FROM records WHERE child_id = ? LIMIT 1";
    }
    $stmt = $db->prepare($sql);
    $ym_param = $ym . "%";

    if ($select === "adult") {
        $stmt->bind_param("sisii", $ym_param, $family_id, $ym_param, $family_id, $family_id);
    } else if ($select === "child") {
        $stmt->bind_param("sisii", $ym_param, $user["id"], $ym_param, $user["id"], $user["id"]);
    }

    sql_check($stmt, $db);
    $stmt->bind_result($month_spending_sum, $month_income_sum);
    $stmt->fetch();
    ?>
    <p>
        <?php echo $select === "adult" ? "支出合計" : "つかったお金"; ?>
        <span class="pc_only">：</span><br class="sp_only">
        <span class="text-red">
            <?php echo number_format($month_spending_sum); ?>円
        </span>
    </p>
    <p>
        <?php echo $select === "adult" ? "収入合計" : "ふえたお金"; ?>
        <span class="pc_only">：</span><br class="sp_only">
        <span class="text-blue">
            <?php echo number_format($month_income_sum); ?>円
        </span>
    </p>
    <p>
        <?php
        $month_sum = $month_income_sum - $month_spending_sum;
        $abs_month_sum = abs($month_sum);
        if ($month_sum < 0) {
            $sign = "-";
            $class = "text-red";
        } else {
            $sign = "";
            $class = "text-blue";
        }
        ?>
        <?php echo $select === "adult" ? "収支合計" : "1か月合計"; ?>
        <span class="pc_only">：</span><br class="sp_only">
        <span class="<?php echo $class ?>">
            <?php echo $sign . number_format($abs_month_sum); ?>円
        </span>
    </p>
    <?php $stmt->close(); ?>
</div>
<!-- </section> -->