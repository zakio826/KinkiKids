<h3 class="pc_only">収支レポート</h3>
<?php
$base_date = strtotime($graph_month); //パラメータもしくは現在の年月のタイムスタンプ
$prev = date("Y-m", strtotime("-1 month", $base_date)); //前月取得
$next = date("Y-m", strtotime("+1 month", $base_date)); //次月取得

if (!isset($_GET["page_id"])) :
    $page_id = 1;
    $graph_link_prev = "?graph_month=" . $prev . "#report";
    $graph_link_next = "?graph_month=" . $next . "#report";
    $graph_link_now = "?graph_month=" . date("Y-m") . "#report";
else :
    $page_id = $_GET["page_id"];
    $graph_link_prev = "?graph_month=" . $prev . "&page_id=" . $page_id . "#report";
    $graph_link_next = "?graph_month=" . $next . "&page_id=" . $page_id . "#report";
    $graph_link_now = "?graph_month=" . date("Y-m") . "&page_id=" . $page_id . "#report";
endif;
?>
<div class="p-monthsearch center">
    <a href="<?php echo $graph_link_prev; ?>">＜</a>
    <input type="month" id="graphMonth" value="<?php echo $graph_month; ?>" onchange="onChangeMonth('graph_month', 'graphMonth');">
    <a href="<?php echo $graph_link_next; ?>">＞</a>
    <a href="<?php echo $graph_link_now; ?>">今月</a>
</div>

<?php if ($select === "adult") : ?>
    <div class="p-calendar__sum center">
        <?php
        $sql = "SELECT (SELECT SUM(amount) FROM records WHERE type = 0 AND date LIKE ? AND family_id = ?)AS spending_sum, (SELECT SUM(amount) FROM records WHERE type = 1 AND date LIKE ? AND family_id = ?)AS income_sum FROM records WHERE family_id = ? LIMIT 1";
        $stmt = $db->prepare($sql);
        $graph_month_param = $graph_month . "%";
        $stmt->bind_param("sisii", $graph_month_param, $family_id, $graph_month_param, $family_id, $family_id);
        sql_check($stmt, $db);
        $stmt->bind_result($month_spending_sum, $month_income_sum);
        $stmt->fetch();
        ?>
        <p>
            支出合計<span class="pc_only">：</span><br class="sp_only">
            <span class="text-red">￥<?php echo number_format($month_spending_sum); ?></span>
        </p>
        <p>
            収入合計<span class="pc_only">：</span><br class="sp_only">
            <span class="text-blue">￥<?php echo number_format($month_income_sum); ?></span>
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
            収支合計<span class="pc_only">：</span><br class="sp_only">
            <span class="<?php echo $class; ?>">
                <?php echo $sign . "￥" . number_format($abs_month_sum); ?>
            </span>
        </p>
        <?php $stmt->close(); ?>
    </div>
    <!-- グラフタブ -->
    <ul class="p-graph-list" id="graphTab">
        <li class="is-active" data-tab="graph-1">支出<br class="sp_only">カテゴリー</li>
        <li data-tab="graph-2">収入<br class="sp_only">カテゴリー</li>
        <li data-tab="graph-3">クレジット<br class="sp_only">カード</li>
        <li data-tab="graph-4">スマホ<br class="sp_only">決済</li>
        <li data-tab="graph-5">個別<br class="sp_only">収支</li>
    </ul>
    <!-- グラフタブ -->

    <!-- 支出カテゴリーグラフ -->
    <div class="p-section__report__graph-box js-graph-content is-active" id="graph-1" data-tab="graph-1">
        <!-- <p>支出カテゴリーグラフ</p> -->
        <?php
        //配列の箱を用意
        $amount_sum = array();
        $item_id = array();
        $item_list = array();

        $sql = "SELECT SUM(records.amount) as sum, spending_category.id, spending_category.name
                FROM records
                LEFT JOIN spending_category ON records.spending_category = spending_category.id
                WHERE records.type = 0 AND records.family_id = ? AND records.date LIKE ?
                GROUP BY records.spending_category
                ORDER BY sum DESC"; //金額の多い順にソート

        $stmt = $db->prepare($sql);
        $stmt->bind_param("is", $family_id, $graph_month_param);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows();
        $stmt->bind_result($amount, $id, $item);

        while ($stmt->fetch()) :
            $amount_sum[] = $amount; //金額を配列に追加
            if (is_null($item)) : //もしカテゴリーがNULLなら
                $item_list[] = "不明"; //「不明」という文字列を変わりに配列に追加
                $item_id[] = "";
            else : //カテゴリーが入力されているときは
                $item_list[] = $item; //そのカテゴリー名を配列に追加
                $item_id[] = $id;
            endif;
        endwhile;

        $json_spendingcat_amount = json_encode($amount_sum);
        $json_spendingcat_item = json_encode($item_list);
        ?>

        <?php if ($count !== 0) : ?>
            <table class="p-table--graph">
                <tr class="head">
                    <th>項目名</th>
                    <th>金額</th>
                </tr>

                <?php
                for ($i = 0; $i < count($item_list); $i++) : ?>
                    <tr>
                        <?php if ($item_list[$i] !== "不明") : ?>
                            <td>
                                <a href="./item-report.php?year=<?php echo mb_substr($graph_month, 0, 4); ?>&item=<?php echo $item_id[$i]; ?>&num=0">
                                    <?php echo $item_list[$i]; ?>
                                </a>
                            </td>
                        <?php else : ?>
                            <td class="unknown">
                                <?php echo $item_list[$i]; ?>
                            </td>
                        <?php endif; ?>


                        <?php if ($item_list[$i] !== "不明") : ?>
                            <td>
                                <a href="./item-report.php?year=<?php echo mb_substr($graph_month, 0, 4); ?>&item=<?php echo $item_id[$i]; ?>&num=0">
                                    <?php echo "￥" . number_format($amount_sum[$i]); ?>
                                </a>
                            </td>
                        <?php else : ?>
                            <td class="unknown">
                                <?php echo "￥" . number_format($amount_sum[$i]); ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endfor; ?>
            </table>
        <?php else : ?>
            <p>データがありません</p>
        <?php endif; ?>
    </div>
    <!-- 支出カテゴリーグラフ -->

    <!-- 収入カテゴリーグラフ -->
    <div class="p-section__report__graph-box js-graph-content" id="graph-2" data-tab="graph-2">
        <?php
        $amount_sum = array();
        $item_id = array();
        $item_list = array();
        $sql = "SELECT SUM(records.amount) as sum, income_category.id, income_category.name
                FROM records
                LEFT JOIN income_category ON records.income_category = income_category.id
                WHERE records.type = 1 AND records.family_id = ? AND records.date LIKE ?
                GROUP BY records.income_category
                ORDER BY sum DESC";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("is", $family_id, $graph_month_param);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows();
        $stmt->bind_result($amount, $id, $item);
        while ($stmt->fetch()) :
            $amount_sum[] = $amount;
            if (is_null($item)) :
                $item_list[] = "不明";
            else :
                $item_list[] = $item;
                $item_id[] = $id;
            endif;
        endwhile;

        $json_incomecat_amount = json_encode($amount_sum);
        $json_incomecat_item = json_encode($item_list);
        ?>

        <?php if ($count !== 0) : ?>
            <table class="p-table--graph">
                <tr class="head">
                    <th>項目名</th>
                    <th>金額</th>
                </tr>

                <?php
                for ($i = 0; $i < count($item_list); $i++) : ?>
                    <tr>
                        <?php if ($item_list[$i] !== "不明") : ?>
                            <td>
                                <a href="./item-report.php?year=<?php echo mb_substr($graph_month, 0, 4); ?>&item=<?php echo $item_id[$i]; ?>&num=1">
                                    <?php echo $item_list[$i]; ?>
                                </a>
                            </td>
                        <?php else : ?>
                            <td class="unknown">
                                <?php echo $item_list[$i]; ?>
                            </td>
                        <?php endif; ?>


                        <?php if ($item_list[$i] !== "不明") : ?>
                            <td>
                                <a href="./item-report.php?year=<?php echo mb_substr($graph_month, 0, 4); ?>&item=<?php echo $item_id[$i]; ?>&num=1">
                                    <?php echo "￥" . number_format($amount_sum[$i]); ?>
                                </a>
                            </td>
                        <?php else : ?>
                            <td class="unknown">
                                <?php echo "￥" . number_format($amount_sum[$i]); ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endfor; ?>
            </table>
        <?php else : ?>
            <p>データがありません</p>
        <?php endif; ?>
    </div>
    <!-- 収入カテゴリーグラフ -->

    <!-- クレジットグラフ -->
    <div class="p-section__report__graph-box js-graph-content" id="graph-3" data-tab="graph3">
        <?php
        $amount_sum = array();
        $item_id = array();
        $item_list = array();
        $sql = "SELECT SUM(records.amount) as sum, creditcard.id, creditcard.name
                FROM records
                LEFT JOIN creditcard ON records.creditcard = creditcard.id
                WHERE records.type = 0 AND records.payment_method = 2 AND records.family_id = ? AND records.date LIKE ?
                GROUP BY records.creditcard
                ORDER BY sum DESC";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("is", $family_id, $graph_month_param);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows();
        $stmt->bind_result($amount, $id, $item);
        while ($stmt->fetch()) :
            $amount_sum[] = $amount;
            if (is_null($item)) :
                $item_list[] = "不明";
            else :
                $item_list[] = $item;
                $item_id[] = $id;
            endif;
        endwhile;

        $json_credit_amount = json_encode($amount_sum);
        $json_credit_item = json_encode($item_list);
        ?>

        <?php if ($count !== 0) : ?>
            <table class="p-table--graph">
                <tr class="head">
                    <th>項目名</th>
                    <th>金額</th>
                </tr>

                <?php
                for ($i = 0; $i < count($item_list); $i++) : ?>
                    <tr>
                        <?php if ($item_list[$i] !== "不明") : ?>
                            <td>
                                <a href="./item-report.php?year=<?php echo mb_substr($graph_month, 0, 4); ?>&item=<?php echo $item_id[$i]; ?>&num=2">
                                    <?php echo $item_list[$i]; ?>
                                </a>
                            </td>
                        <?php else : ?>
                            <td class="unknown">
                                <?php echo $item_list[$i]; ?>
                            </td>
                        <?php endif; ?>


                        <?php if ($item_list[$i] !== "不明") : ?>
                            <td>
                                <a href="./item-report.php?year=<?php echo mb_substr($graph_month, 0, 4); ?>&item=<?php echo $item_id[$i]; ?>&num=2">
                                    <?php echo "￥" . number_format($amount_sum[$i]); ?>
                                </a>
                            </td>
                        <?php else : ?>
                            <td class="unknown">
                                <?php echo "￥" . number_format($amount_sum[$i]); ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endfor; ?>
            </table>
        <?php else : ?>
            <p>データがありません</p>
        <?php endif; ?>
    </div>
    <!-- クレジットグラフ -->

    <!-- スマホ決済グラフ -->
    <div class="p-section__report__graph-box js-graph-content" id="graph-4" data-tab="graph-4">
        <?php
        $amount_sum = array();
        $item_id = array();
        $item_list = array();
        $sql = "SELECT SUM(records.amount) as sum, qr.id, qr.name
                FROM records
                LEFT JOIN qr ON records.qr = qr.id
                WHERE records.type = 0 AND records.payment_method = 3 AND records.family_id = ? AND records.date LIKE ?
                GROUP BY records.qr
                ORDER BY sum DESC";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("is", $family_id, $graph_month_param);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows();
        $stmt->bind_result($amount, $id, $item);
        while ($stmt->fetch()) :
            $amount_sum[] = $amount;
            if (is_null($item)) :
                $item_list[] = "不明";
            else :
                $item_list[] = $item;
                $item_id[] = $id;
            endif;
        endwhile;

        $json_qr_amount = json_encode($amount_sum);
        $json_qr_item = json_encode($item_list);
        ?>

        <?php if ($count !== 0) : ?>
            <table class="p-table--graph">
                <tr class="head">
                    <th>項目名</th>
                    <th>金額</th>
                </tr>

                <?php
                for ($i = 0; $i < count($item_list); $i++) : ?>
                    <tr>
                        <?php if ($item_list[$i] !== "不明") : ?>
                            <td>
                                <a href="./item-report.php?year=<?php echo mb_substr($graph_month, 0, 4); ?>&item=<?php echo $item_id[$i]; ?>&num=3">
                                    <?php echo $item_list[$i]; ?>
                                </a>
                            </td>
                        <?php else : ?>
                            <td class="unknown">
                                <?php echo $item_list[$i]; ?>
                            </td>
                        <?php endif; ?>


                        <?php if ($item_list[$i] !== "不明") : ?>
                            <td>
                                <a href="./item-report.php?year=<?php echo mb_substr($graph_month, 0, 4); ?>&item=<?php echo $item_id[$i]; ?>&num=3">
                                    <?php echo "￥" . number_format($amount_sum[$i]); ?>
                                </a>
                            </td>
                        <?php else : ?>
                            <td class="unknown">
                                <?php echo "￥" . number_format($amount_sum[$i]); ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endfor; ?>
            </table>
        <?php else : ?>
            <p>データがありません</p>
        <?php endif; ?>
    </div>

    <!-- 個別 -->
    <div class="p-section__report__graph-box js-graph-content" id="graph-5" data-tab="graph-5">
        <?php
        $amount_sum = array();
        $item_id = array();
        $item_list = array();
        $sql = "SELECT SUM(records.amount) as sum, child.id, child.child_name
                FROM records
                LEFT JOIN child ON records.child_id = child.id
                WHERE records.child_id != 0 AND records.family_id = ? AND records.date LIKE ?
                GROUP BY records.child_id
                ORDER BY sum DESC";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("is", $family_id, $graph_month_param);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows();
        $stmt->bind_result($amount, $id, $item);
        while ($stmt->fetch()) :
            $amount_sum[] = $amount;
            if (is_null($item)) :
                $item_list[] = "不明";
            else :
                $item_list[] = $item;
                $item_id[] = $id;
            endif;
        endwhile;

        $json_child_amount = json_encode($amount_sum);
        $json_child_item = json_encode($item_list);
        ?>

        <?php if ($count !== 0) : ?>
            <table class="p-table--graph">
                <tr class="head">
                    <th>項目名</th>
                    <th>金額</th>
                </tr>

                <?php
                for ($i = 0; $i < count($item_list); $i++) : ?>
                    <tr>
                        <?php if ($item_list[$i] !== "不明") : ?>
                            <td>
                                <a href="./item-report.php?year=<?php echo mb_substr($graph_month, 0, 4); ?>&item=<?php echo $item_id[$i]; ?>&num=3">
                                    <?php echo $item_list[$i]; ?>
                                </a>
                            </td>
                        <?php else : ?>
                            <td class="unknown">
                                <?php echo $item_list[$i]; ?>
                            </td>
                        <?php endif; ?>


                        <?php if ($item_list[$i] !== "不明") : ?>
                            <td>
                                <a href="./item-report.php?year=<?php echo mb_substr($graph_month, 0, 4); ?>&item=<?php echo $item_id[$i]; ?>&num=3">
                                    <?php echo "￥" . number_format($amount_sum[$i]); ?>
                                </a>
                            </td>
                        <?php else : ?>
                            <td class="unknown">
                                <?php echo "￥" . number_format($amount_sum[$i]); ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endfor; ?>
            </table>
        <?php else : ?>
            <p>データがありません</p>
        <?php endif; ?>
    </div>
    <!-- スマホ決済グラフ -->
<?php endif; ?>

<?php if ($select === "child") : ?>
    <div class="p-calendar__sum center">
        <?php
        $sql = "SELECT (SELECT SUM(amount) FROM records WHERE type = 0 AND date LIKE ? AND child_id = ?)AS spending_sum, (SELECT SUM(amount) FROM records WHERE type = 1 AND date LIKE ? AND child_id = ?)AS income_sum FROM records WHERE child_id = ? LIMIT 1";
        $stmt = $db->prepare($sql);
        $graph_month_param = $graph_month . "%";
        $stmt->bind_param("sisii", $graph_month_param, $user["id"], $graph_month_param, $user["id"], $user["id"]);
        sql_check($stmt, $db);
        $stmt->bind_result($month_spending_sum, $month_income_sum);
        $stmt->fetch();
        ?>
        <p>
            支出合計<span class="pc_only">：</span><br class="sp_only">
            <span class="text-red">￥<?php echo number_format($month_spending_sum); ?></span>
        </p>
        <p>
            収入合計<span class="pc_only">：</span><br class="sp_only">
            <span class="text-blue">￥<?php echo number_format($month_income_sum); ?></span>
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
            収支合計<span class="pc_only">：</span><br class="sp_only">
            <span class="<?php echo $class; ?>">
                <?php echo $sign . "￥" . number_format($abs_month_sum); ?>
            </span>
        </p>
        <?php $stmt->close(); ?>
    </div>
    <!-- グラフタブ -->
    <ul class="p-graph-list" id="graphTab">
        <li class="is-active" data-tab="graph-1">支出<br class="sp_only">カテゴリー</li>
        <li data-tab="graph-2">収入<br class="sp_only">カテゴリー</li>
    </ul>
    <!-- グラフタブ -->

    <!-- 支出カテゴリーグラフ -->
    <div class="p-section__report__graph-box js-graph-content is-active" id="graph-1" data-tab="graph-1">
        <!-- <p>支出カテゴリーグラフ</p> -->
        <?php
        //配列の箱を用意
        $amount_sum = array();
        $item_id = array();
        $item_list = array();

        $sql = "SELECT SUM(records.amount) as sum, spending_category.id, spending_category.name
                FROM records
                LEFT JOIN spending_category ON records.spending_category = spending_category.id
                WHERE records.type = 0 AND records.child_id = ? AND records.date LIKE ?
                GROUP BY records.spending_category
                ORDER BY sum DESC"; //金額の多い順にソート

        $stmt = $db->prepare($sql);
        $stmt->bind_param("is", $user["id"], $graph_month_param);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows();
        $stmt->bind_result($amount, $id, $item);

        while ($stmt->fetch()) :
            $amount_sum[] = $amount; //金額を配列に追加
            if (is_null($item)) : //もしカテゴリーがNULLなら
                $item_list[] = "不明"; //「不明」という文字列を変わりに配列に追加
                $item_id[] = "";
            else : //カテゴリーが入力されているときは
                $item_list[] = $item; //そのカテゴリー名を配列に追加
                $item_id[] = $id;
            endif;
        endwhile;

        $json_spendingcat_amount = json_encode($amount_sum);
        $json_spendingcat_item = json_encode($item_list);
        ?>

        <?php if ($count !== 0) : ?>
            <table class="p-table--graph">
                <tr class="head">
                    <th>項目名</th>
                    <th>金額</th>
                </tr>

                <?php
                for ($i = 0; $i < count($item_list); $i++) : ?>
                    <tr>
                        <?php if ($item_list[$i] !== "不明") : ?>
                            <td>
                                <a href="./item-report.php?year=<?php echo mb_substr($graph_month, 0, 4); ?>&item=<?php echo $item_id[$i]; ?>&num=0">
                                    <?php echo $item_list[$i]; ?>
                                </a>
                            </td>
                        <?php else : ?>
                            <td class="unknown">
                                <?php echo $item_list[$i]; ?>
                            </td>
                        <?php endif; ?>


                        <?php if ($item_list[$i] !== "不明") : ?>
                            <td>
                                <a href="./item-report.php?year=<?php echo mb_substr($graph_month, 0, 4); ?>&item=<?php echo $item_id[$i]; ?>&num=0">
                                    <?php echo "￥" . number_format($amount_sum[$i]); ?>
                                </a>
                            </td>
                        <?php else : ?>
                            <td class="unknown">
                                <?php echo "￥" . number_format($amount_sum[$i]); ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endfor; ?>
            </table>
        <?php else : ?>
            <p>データがありません</p>
        <?php endif; ?>
    </div>
    <!-- 支出カテゴリーグラフ -->

    <!-- 収入カテゴリーグラフ -->
    <div class="p-section__report__graph-box js-graph-content" id="graph-2" data-tab="graph-2">
        <?php
        $amount_sum = array();
        $item_id = array();
        $item_list = array();
        $sql = "SELECT SUM(records.amount) as sum, income_category.id, income_category.name
                FROM records
                LEFT JOIN income_category ON records.income_category = income_category.id
                WHERE records.type = 1 AND records.child_id = ? AND records.date LIKE ?
                GROUP BY records.income_category
                ORDER BY sum DESC";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("is", $user["id"], $graph_month_param);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows();
        $stmt->bind_result($amount, $id, $item);
        while ($stmt->fetch()) :
            $amount_sum[] = $amount;
            if (is_null($item)) :
                $item_list[] = "不明";
            else :
                $item_list[] = $item;
                $item_id[] = $id;
            endif;
        endwhile;

        $json_incomecat_amount = json_encode($amount_sum);
        $json_incomecat_item = json_encode($item_list);
        ?>

        <?php if ($count !== 0) : ?>
            <table class="p-table--graph">
                <tr class="head">
                    <th>項目名</th>
                    <th>金額</th>
                </tr>

                <?php
                for ($i = 0; $i < count($item_list); $i++) : ?>
                    <tr>
                        <?php if ($item_list[$i] !== "不明") : ?>
                            <td>
                                <a href="./item-report.php?year=<?php echo mb_substr($graph_month, 0, 4); ?>&item=<?php echo $item_id[$i]; ?>&num=1">
                                    <?php echo $item_list[$i]; ?>
                                </a>
                            </td>
                        <?php else : ?>
                            <td class="unknown">
                                <?php echo $item_list[$i]; ?>
                            </td>
                        <?php endif; ?>


                        <?php if ($item_list[$i] !== "不明") : ?>
                            <td>
                                <a href="./item-report.php?year=<?php echo mb_substr($graph_month, 0, 4); ?>&item=<?php echo $item_id[$i]; ?>&num=1">
                                    <?php echo "￥" . number_format($amount_sum[$i]); ?>
                                </a>
                            </td>
                        <?php else : ?>
                            <td class="unknown">
                                <?php echo "￥" . number_format($amount_sum[$i]); ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endfor; ?>
            </table>
        <?php else : ?>
            <p>データがありません</p>
        <?php endif; ?>
    </div>
    <!-- 収入カテゴリーグラフ -->
<?php endif; ?>