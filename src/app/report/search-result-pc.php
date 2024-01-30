<table class="p-table p-table--record-output" id="all-table">
    <?php if ($select === "adult") : ?>
        <!-- 貯蓄額出力行 -->
        <tr class="p-table__join-row">
            <?php
            //支出収支金額の抽出
            $sql = "SELECT (SELECT SUM(amount) FROM records WHERE type = 0 AND user_id = ?)AS spending, (SELECT SUM(amount) FROM records WHERE type = 1 AND user_id = ?)AS income FROM records WHERE user_id = ? LIMIT 1";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("iii", $user["id"], $user["id"], $user["id"]);
            sql_check($stmt, $db);
            $stmt->bind_result($spending_amount, $income_amount);
            $sum = 0;
            while ($stmt->fetch()) :
                $sum = $income_amount - $spending_amount;
            endwhile;

            //貯蓄額の計算
            $all_sum = $initial_savings + $sum;
            $abs_all_sum = abs($all_sum);
            if ($all_sum < 0) :
                $sign = "-";
            else :
                $sign = "";
            endif;
            ?>
            <th colspan="7">現在の貯蓄額<span class=""><?php echo $sign . "￥" . number_format($abs_all_sum); ?></span></th>
        </tr>

        <!-- タイトル行 -->
        <tr class="p-table__head">
            <th>収支日</th>
            <th>名前</th>
            <th>タイトル</th>
            <th>収入</th>
            <th>支出</th>
            <th>支出詳細</th>
            <th>操作</th>
        </tr>

        <?php
        $add_where_month = "WHERE records.date LIKE ? AND records.user_id = ? ";
        $add_where_date = "WHERE records.date = ? AND records.user_id = ? ";

        if (isset($_POST["detail-search"])) :
            //タイトルキーワードのWHERE文作成
            $add_sql_title = add_sql_title($filtering_title);
            //上記関数で返ってきたWHERE文を$add_where_sqlに追加
            $add_where_month .= $add_sql_title;
            $add_where_date .= $add_sql_title;
            //選択項目のカラム配列
            $filter_column = ["records.child_id", "records.spending_category", "records.income_category", "records.payment_method", "records.creditcard", "records.qr"];
            //選択項目の値配列
            $filter_value = [$filtering_child, $filtering_spendingcat, $filtering_incomecat, $filtering_paymentmethod, $filtering_credit, $filtering_qr];

            //繰り返し構文で上記2つの配列を使用しながら、選択項目のWHERE分を作成し追加する
            for ($i = 0; $i < count($filter_column); $i++) :
                //各選択項目のWHERE文作成
                $add_sql = add_sql_item($filter_column[$i], $filter_value[$i]);
                //上記関数で返ってきたWHERE文を$add_where_sqlに追加
                $add_where_month .= $add_sql;
                $add_where_date .= $add_sql;
            endfor;
        endif;

        $add_order = "ORDER BY date DESC, input_time DESC ";
        $add_limit = "LIMIT ?, ?";

        $sql_dataoutput = "SELECT records.id, records.date, records.title, records.amount,
                            spending_category.name, income_category.name, records.type,
                            payment_method.name, creditcard.name, qr.name, records.memo, records.input_time, child.name
                            FROM records
                            LEFT JOIN spending_category ON records.spending_category = spending_category.id
                            LEFT JOIN income_category ON records.income_category = income_category.id
                            LEFT JOIN payment_method ON records.payment_method = payment_method.id
                            LEFT JOIN creditcard ON records.creditcard = creditcard.id
                            LEFT JOIN child ON records.child_id = child.id
                            LEFT JOIN qr ON records.qr = qr.id ";
        $stmt_dataoutput = $db->prepare($sql_dataoutput . $add_where_month . $add_order . $add_limit);
        $month_param = $search_month . "%";

        $stmt_dataoutput->bind_param("ssii", $month_param, $user["id"], $page_param, $max_view);
        sql_check($stmt_dataoutput, $db);
        $stmt_dataoutput->store_result();
        $count = $stmt_dataoutput->num_rows();

        $stmt_dataoutput->bind_result(
            $id,
            $date,
            $title,
            $amount,
            $spending_category,
            $income_category,
            $type,
            $paymentmethod,
            $credit,
            $qr,
            $memo,
            $input_time,
            $child_name,
        );

        while ($stmt_dataoutput->fetch()) : ?>
            <!-- 収支データ出力 -->
            <tr class="p-table__item item<?php echo h($id); ?> <?php echo $memo !== "" ? "hasmemo" : ""; ?>">
                <td><?php echo date("Y/m/d", strtotime($date)); ?></td>
                <td><?php echo $child_name; ?></td>
                <td>
                    <?php echo h($title); ?>
                    <span>
                        <?php
                        if ($type === 0 && $spending_category !== null) :
                            echo "(" . h($spending_category) . ")";
                        elseif ($type === 1 && $income_category !== null) :
                            echo "(" . $income_category . ")";
                        else :
                            echo "(不明)";
                        endif;
                        ?>
                        <i class="fa-regular fa-message" onclick="showMemo('<?php echo h($memo); ?>')"></i> </span>
                </td>
                <td>
                    <?php echo $type === 1 ? "¥" . number_format(h($amount)) : ""; ?>
                </td>


                <td>
                    <?php echo $type === 0 ? "¥" . number_format($amount) : ""; ?>
                    <span>
                        <?php
                        if ($type === 0 && $paymentmethod !== null) {
                            echo "(" . h($paymentmethod) . ")";
                        } else if ($type === 1) {
                            echo "";
                        } else {
                            echo "(不明)";
                        }
                        ?>
                    </span>
                <td>
                    <?php echo $paymentmethod === "クレジットカード" ? h($credit) : "" ?>
                    <?php echo $paymentmethod === "スマホ決済" ? h($qr) : "" ?>
                </td>
                <td>
                    <form action="./record-edit.php" method="POST">
                        <input type="hidden" name="record_id" value="<?php echo h($id); ?>">
                        <input type="submit" class="c-button c-button--bg-green edit fas" id="" value="">
                    </form>
                    <a class="c-button c-button--bg-red delete" id="delete<?php echo h($id); ?>" href="./delete.php?id=<?php echo h($id); ?>&from=index" onclick="deleteConfirm("<?php echo h($title); ?>", "delete<?php echo h($id) ?>");">
                        <i class="fa-regular fa-trash-can"></i>
                    </a>
                </td>
                </td>
            <?php endwhile; ?>

            <?php if ($count === 0) : ?>
            <tr class="nodata">
                <td colspan="6">データがありません</td>
            </tr>
        <?php endif; ?>

        <!-- 合計金額行 -->
        <?php
        $sql = "SELECT (SELECT SUM(amount) FROM records WHERE type=1 AND user_id = ? AND date LIKE ?) AS income, (SELECT SUM(amount) FROM records WHERE type=0 AND user_id = ? AND date LIKE ?) AS spending FROM records WHERE user_id = ? AND date LIKE ? LIMIT 1";
        $stmt->prepare($sql);
        $stmt->bind_param("isisis", $user["id"], $month_param, $user["id"], $month_param, $user["id"], $month_param);
        sql_check($stmt, $db);
        $stmt->bind_result($income_search, $spending_search);
        $sum_search = 0;
        while ($stmt->fetch()) :
            $sum_search = $income_search - $spending_search;
        endwhile;

        $abs_sum_search = abs($sum_search);
        if ($sum_search >= 0) :
            $sign_search = "";
            $class = "text-blue";
        else :
            $sign_search = "-";
            $class = "text-red";
        endif;
        ?>

        <tr class="p-table__foot">
            <th colspan="3">合計金額 </th>
            <th class="text-blue">
                <?php echo "￥" . number_format($income_search); ?>
            </th>
            <th class="text-red">
                <?php echo "￥" . number_format($spending_search); ?>
            </th>
            <th colspan="2" class="<?php echo $class; ?>">
                合計 <?php echo $sign_search . "￥" . number_format($abs_sum_search); ?>
            </th>
        </tr>
        <!-- //合計金額行 -->

    <?php endif; ?>
    <?php if ($select === "child") : ?>
        <!-- 貯蓄額出力行 -->
        <tr class="p-table__join-row">
            <?php
            //支出収支金額の抽出
            $sql = "SELECT (SELECT SUM(amount) FROM records WHERE type = 0 AND child_id = ?)AS spending, (SELECT SUM(amount) FROM records WHERE type = 1 AND child_id = ?)AS income FROM records WHERE child_id = ? LIMIT 1";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("iii", $user["id"], $user["id"], $user["id"]);
            sql_check($stmt, $db);
            $stmt->bind_result($spending_amount, $income_amount);
            $sum = 0;
            while ($stmt->fetch()) :
                $sum = $income_amount - $spending_amount;
            endwhile;

            //貯蓄額の計算
            $all_sum = $savings + $sum;
            $abs_all_sum = abs($all_sum);
            if ($all_sum < 0) :
                $sign = "-";
            else :
                $sign = "";
            endif;
            ?>
            <th colspan="6">たまっているお金<span class=""><?php echo $sign . "￥" . number_format($abs_all_sum); ?></span></th>
        </tr>

        <!-- タイトル行 -->
        <tr class="p-table__head">
            <th>収支日</th>
            <th>タイトル</th>
            <th>もらったお金</th>
            <th>つかつかったお金</th>
            <th>つかったおかねをくわしく</th>
            <th>そうさ</th>
        </tr>

        <?php
        $add_where_month = "WHERE records.date LIKE ? AND records.child_id = ? ";
        $add_where_date = "WHERE records.date = ? AND records.child_id = ? ";

        if (isset($_POST["detail-search"])) :
            //タイトルキーワードのWHERE文作成
            $add_sql_title = add_sql_title($filtering_title);
            //上記関数で返ってきたWHERE文を$add_where_sqlに追加
            $add_where_month .= $add_sql_title;
            $add_where_date .= $add_sql_title;
            //選択項目のカラム配列
            $filter_column = ["records.child_id", "records.spending_category", "records.income_category", "records.payment_method", "records.creditcard", "records.qr"];
            //選択項目の値配列
            $filter_value = [$filtering_child, $filtering_spendingcat, $filtering_incomecat, $filtering_paymentmethod, $filtering_credit, $filtering_qr];

            //繰り返し構文で上記2つの配列を使用しながら、選択項目のWHERE分を作成し追加する
            for ($i = 0; $i < count($filter_column); $i++) :
                //各選択項目のWHERE文作成
                $add_sql = add_sql_item($filter_column[$i], $filter_value[$i]);
                //上記関数で返ってきたWHERE文を$add_where_sqlに追加
                $add_where_month .= $add_sql;
                $add_where_date .= $add_sql;
            endfor;
        endif;

        $add_order = "ORDER BY date DESC, input_time DESC ";
        $add_limit = "LIMIT ?, ?";

        $sql_dataoutput = "SELECT records.id, records.date, records.title, records.amount,
                            spending_category.name, income_category.name, records.type,
                            payment_method.name, creditcard.name, qr.name, records.memo, records.input_time
                            FROM records
                            LEFT JOIN spending_category ON records.spending_category = spending_category.id
                            LEFT JOIN income_category ON records.income_category = income_category.id
                            LEFT JOIN payment_method ON records.payment_method = payment_method.id
                            LEFT JOIN creditcard ON records.creditcard = creditcard.id
                            LEFT JOIN qr ON records.qr = qr.id ";
        $stmt_dataoutput = $db->prepare($sql_dataoutput . $add_where_month . $add_order . $add_limit);
        $month_param = $search_month . "%";

        $stmt_dataoutput->bind_param("siii", $month_param, $user["id"], $page_param, $max_view);
        sql_check($stmt_dataoutput, $db);
        $stmt_dataoutput->store_result();
        $count = $stmt_dataoutput->num_rows();

        $stmt_dataoutput->bind_result(
            $id,
            $date,
            $title,
            $amount,
            $spending_category,
            $income_category,
            $type,
            $paymentmethod,
            $credit,
            $qr,
            $memo,
            $input_time
        );

        while ($stmt_dataoutput->fetch()) : ?>

            <!-- 収支データ出力 -->
            <tr class="p-table__item item<?php echo h($id); ?> <?php echo $memo !== "" ? "hasmemo" : ""; ?>">
                <td><?php echo date("Y/m/d", strtotime($date)); ?></td>
                <td>
                    <?php echo h($title); ?>
                    <span>
                        <?php
                        if ($type === 0 && $spending_category !== null) :
                            echo "(" . h($spending_category) . ")";
                        elseif ($type === 1 && $income_category !== null) :
                            echo "(" . $income_category . ")";
                        else :
                            echo "(不明)";
                        endif;
                        ?>
                        <i class="fa-regular fa-message" onclick="showMemo('<?php echo h($memo); ?>')"></i> </span>
                </td>
                <td>
                    <?php echo $type === 1 ? "¥" . number_format(h($amount)) : ""; ?>
                </td>


                <td>
                    <?php echo $type === 0 ? "¥" . number_format($amount) : ""; ?>
                    <span>
                        <?php
                        if ($type === 0 && $paymentmethod !== null) {
                            echo "(" . h($paymentmethod) . ")";
                        } else if ($type === 1) {
                            echo "";
                        } else {
                            echo "(不明)";
                        }
                        ?>
                    </span>
                <td>
                    <?php echo $paymentmethod === "クレジットカード" ? h($credit) : "" ?>
                    <?php echo $paymentmethod === "スマホ決済" ? h($qr) : "" ?>
                </td>
                <td>
                    <form action="./record-edit.php" method="POST">
                        <input type="hidden" name="record_id" value="<?php echo h($id); ?>">
                        <input type="submit" class="c-button c-button--bg-green edit fas" id="" value="">
                    </form>
                    <a class="c-button c-button--bg-red delete" id="delete<?php echo h($id); ?>" href="./delete.php?id=<?php echo h($id); ?>&from=index" onclick="deleteConfirm("<?php echo h($title); ?>", "delete<?php echo h($id) ?>");">
                        <i class="fa-regular fa-trash-can"></i>
                    </a>
                </td>
                </td>
            <?php endwhile; ?>

            <?php if ($count === 0) : ?>
            <tr class="nodata">
                <td colspan="6">データがありません</td>
            </tr>
        <?php endif; ?>

        <!-- 合計金額行 -->
        <?php
        $sql = "SELECT (SELECT SUM(amount) FROM records WHERE type=1 AND child_id = ? AND date LIKE ?) AS income, (SELECT SUM(amount) FROM records WHERE type=0 AND child_id = ? AND date LIKE ?) AS spending FROM records WHERE child_id = ? AND date LIKE ? LIMIT 1";
        $stmt->prepare($sql);
        $stmt->bind_param("isisis", $user["id"], $month_param, $user["id"], $month_param, $user["id"], $month_param);
        sql_check($stmt, $db);
        $stmt->bind_result($income_search, $spending_search);
        $sum_search = 0;
        while ($stmt->fetch()) :
            $sum_search = $income_search - $spending_search;
        endwhile;

        $abs_sum_search = abs($sum_search);
        if ($sum_search >= 0) :
            $sign_search = "";
            $class = "text-blue";
        else :
            $sign_search = "-";
            $class = "text-red";
        endif;
        ?>

        <tr class="p-table__foot">
            <th colspan="2">合計金額 </th>
            <th class="text-blue">
                <?php echo "￥" . number_format($income_search); ?>
            </th>
            <th class="text-red">
                <?php echo "￥" . number_format($spending_search); ?>
            </th>
            <th colspan="2" class="<?php echo $class; ?>">
                合計 <?php echo $sign_search . "￥" . number_format($abs_sum_search); ?>
            </th>
        </tr>
        <!-- //合計金額行 -->
    <?php endif; ?>

</table>

<!--500行目付近-->
<div class="p-pagenation">
    <?php
    if ($select === "adult") {
        $sql = "SELECT count(*) FROM records WHERE records.date LIKE ? AND records.user_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("si", $month_param, $user["id"]);
    } elseif ($select === "child") {
        $sql = "SELECT count(*) FROM records WHERE records.date LIKE ? AND records.child_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("si", $month_param, $user["id"]);
    }
    sql_check($stmt, $db);
    $stmt->bind_result($record_count);
    $stmt->fetch();
    $stmt->close();
    $pages = ceil($record_count / $max_view);
    ?>
    <?php for ($i = 1; $i <= $pages; $i++) : ?>
        <?php if ($i == $page_id) : ?>
            <span><?php echo $i; ?></span>
        <?php elseif ($i != $page_id && !isset($_GET["ym"]) && !isset($_GET["search_month"])) : ?>
            <a href="./index.php?page_id=<?php echo $i; ?>#data-table"><?php echo $i; ?></a>
        <?php else : ?>
            <!-- <a href="./index.php?ym=<?php echo $ym; ?>&page_id=<?php echo $i; ?>#data-table"><?php echo $i; ?></a> -->
            <a href="./index.php?search_month=<?php echo date("Y-m", $base_date); ?>&page_id=<?php echo $i; ?>#data-table"><?php echo $i; ?></a>
        <?php endif; ?>
    <?php endfor; ?>
</div>
