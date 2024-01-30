<div class="excel-button">
    <button type="button" id="excelExport">Excel出力</button>
</div>

<table class="p-table p-table--hide" id="table">
    <!-- タイトル行 -->
    <?php if ($select === "adult") : ?>
        <tr>
            <th>収支日</th>
            <th>子ども名</th>
            <th>タイトル</th>
            <th>カテゴリー</th>
            <th>収入</th>
            <th>支出</th>
            <th>支払い方法</th>
            <th>クレジットカード</th>
            <th>スマホ決済</th>
        </tr>

        <!-- 収支データ出力 -->

        <?php
        $stmt = $db->prepare($sql_dataoutput . $add_where_month . $add_order);
        $stmt->bind_param("si", $month_param, $user["id"]);
        $success = $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows();

        $stmt->bind_result(
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
        ); ?>

        <?php while ($stmt->fetch()) : ?>

            <tr>
                <td><?php echo date($date); ?></td>
                <td><?php echo h($title); ?></td>
                <td>
                    <?php
                    if ($type === 0 && $spending_category !== null) {
                        echo h($spending_category);
                    } else if ($type === 1 && $income_category !== null) {
                        echo h($income_category);
                    } else {
                        echo "不明";
                    }
                    ?>
                </td>
                <td>
                    <?php echo $type === 1 ? "¥" . number_format(h($amount)) : ""; ?>
                </td>
                <td>
                    <?php echo $type === 0 ? "¥" . number_format($amount) : ""; ?>
                </td>
                <td><?php echo $type === 0 ? $paymentmethod : ""; ?></td>
                <td>
                    <?php echo $paymentmethod === "クレジット" ? h($credit) : "" ?>
                </td>
                <td>
                    <?php echo $paymentmethod === "スマホ決済" ? h($qr) : "" ?>
                </td>

            </tr>

        <?php endwhile; ?>
    <?php endif; ?>

    <?php if ($select === "child") : ?>
        <tr>
            <th>収支日</th>
            <th>タイトル</th>
            <th>カテゴリー</th>
            <th>収入</th>
            <th>支出</th>
            <th>支払い方法</th>
            <th>クレジットカード</th>
            <th>スマホ決済</th>
        </tr>

        <!-- 収支データ出力 -->

        <?php
        $stmt = $db->prepare($sql_dataoutput . $add_where_month . $add_order);
        $stmt->bind_param("si", $month_param, $user["id"]);
        $success = $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows();

        $stmt->bind_result(
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
        ); ?>

        <?php while ($stmt->fetch()) : ?>

            <tr>
                <td><?php echo date($date); ?></td>
                <td><?php echo h($title); ?></td>
                <td>
                    <?php
                    if ($type === 0 && $spending_category !== null) {
                        echo h($spending_category);
                    } else if ($type === 1 && $income_category !== null) {
                        echo h($income_category);
                    } else {
                        echo "不明";
                    }
                    ?>
                </td>
                <td>
                    <?php echo $type === 1 ? "¥" . number_format(h($amount)) : ""; ?>
                </td>
                <td>
                    <?php echo $type === 0 ? "¥" . number_format($amount) : ""; ?>
                </td>
                <td><?php echo $type === 0 ? $paymentmethod : ""; ?></td>
                <td>
                    <?php echo $paymentmethod === "クレジット" ? h($credit) : "" ?>
                </td>
                <td>
                    <?php echo $paymentmethod === "スマホ決済" ? h($qr) : "" ?>
                </td>

            </tr>

        <?php endwhile; ?>
    <?php endif; ?>
    <!-- //収支データ出力 -->
</table>