<!-- 総貯蓄額 -->
<p class="p-sp-data-box__head" style="text-align: center;"><?php echo $select === "adult" ? "現在の貯蓄額" : "たまったお金"; ?><span class=""><?php echo $sign . "￥" . number_format($abs_all_sum); ?></span></p>
<!-- //総貯蓄額 -->
<ul class="p-sp-data-box__item-sum">
    <li><?php echo $select === "adult" ? "収入" : "はいったお金"; ?><br><span class="text-blue"><?php echo "￥" . number_format($income_search); ?></span></li>
    <li><?php echo $select === "adult" ? "支出" : "つかったお金"; ?><br><span class="text-red"><?php echo "￥" . number_format($spending_search); ?></span></li>
    <li><?php echo $select === "adult" ? "合計" : "のこりのお金"; ?><br><span class="<?php echo $class ?>"><?php echo $sign_search . "￥" . number_format($abs_sum_search); ?></span></li>
</ul>

<div class="p-togglebutton-box">
    <label for="toggleStyle" class="u-flex-box">
        <span>日付ごとまとめて表示 </span>
        <div>
            <input type="checkbox" id="toggleStyle" onchange="onChangeListView();">
            <div class="circle"></div>
            <div class="button"></div>
        </div>
    </label>
</div>

<!-- 収支データ出力 -->
<?php if ($select === "adult") : ?>
    <?php
    //月データ日付まとめで表示
    $date_list = array(); //データがある日付を配列で入れる箱を用意
    $count_list = array(); //各日付されているデータ数を配列で入れる箱を用意
    $week_list = ["日", "月", "火", "水", "木", "金", "土"]; //日本語曜日配列の用意

    //日付でグループ化したデータを抽出
    $sql = "SELECT COUNT(*), date FROM records WHERE user_id = ? AND date LIKE ?";
    if (isset($_POST["detail-search"])) :
        $add_sql_title = add_sql_title($filtering_title);
        $sql .= $add_sql_title;
        for ($i = 0; $i < count($filter_column); $i++) :
            $add_sql = add_sql_item($filter_column[$i], $filter_value[$i]);
            $sql .= $add_sql;
        endfor;
    endif;
    $sql .= " GROUP BY date ORDER BY date";

    $stmt = $db->prepare($sql);
    $stmt->bind_param("is", $user["id"], $month_param);
    $stmt->execute();
    $stmt->store_result();
    $count = $stmt->num_rows();
    $stmt->bind_result($count_item, $date_item);
    while ($stmt->fetch()) :
        $date_list[] = $date_item; //日付データを取り出し配列に入れる
        $count_list[] = $count_item; //各日付に登録されているデータ数を取り出し配列に入れる
    endwhile;
    ?>
    <?php if ($count > 0) : ?>
        <div id="groupView" class="p-sp-data-box__groupview hide" style="height: 200px;">
            <?php
            for ($i = 0; $i < count($date_list); $i++) :
                $search_date = $date_list[$i];
                $create_week = date("w", strtotime($search_date));
                $day_of_week = $week_list[$create_week];
            ?>
                <div class="p-toggledate-tab js-toggle" id="date<?php echo h($search_date); ?>" onclick="onClickDataBanner('<?php echo $search_date; ?>');">
                    <p class="date">
                        <?php echo date("n月j日", strtotime($date_list[$i])); ?>
                        <span class="day-of-week">(<?php echo ($day_of_week); ?>)</span>
                    </p>
                    <p class="count">(<?php echo h($count_list[$i]); ?>件 )</p>
                </div>
                <div class="p-sp-data-box__frame hide" id="item<?php echo $search_date; ?>">
                    <?php
                    $stmt_dataoutput = $db->prepare($sql_dataoutput . $add_where_date);
                    $stmt_dataoutput->bind_param("si", $search_date, $user["id"]);
                    sql_check($stmt_dataoutput, $db);
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
                        $name
                    );
                    while ($stmt_dataoutput->fetch()) : ?>
                        <div class="p-sp-data-box item<?php echo h($id); ?>">
                            <div class="u-flex-box p-sp-data-box__overview <?php echo $memo !== "" ? "hasmemo" : ""; ?>">
                                <p> <?php echo h($title); ?>
                                    <span>
                                        <?php
                                        if ($type === 0 && $spending_category !== null) {
                                            echo "(" . h($spending_category) . ")";
                                        } else if ($type === 1 && $income_category !== null) {
                                            echo "(" . h($income_category) . ")";
                                        } else {
                                            echo "(カテゴリー不明)";
                                        }
                                        ?>
                                        <i class="fa-regular fa-message" onclick="showMemo('<?php echo h($memo); ?>')"></i> </span>
                                </p>
                                <p class="<?php echo $type === 0 ? "text-red" : "text-blue" ?>">
                                    <?php echo h($type) === "0" ? "-￥" . number_format($amount) : ""; ?>
                                    <?php echo h($type) === "1" ? "+￥" . number_format($amount) : ""; ?>
                                </p>
                            </div>
                            <div class="p-sp-data-box__detail">
                                <p>
                                    <?php
                                    //支払い方法の出力
                                    if ($type === 0 && $paymentmethod !== null) {
                                        echo "支払い方法：" . h($paymentmethod);
                                    } else if ($type === 1) {
                                        echo "";
                                    } else {
                                        echo "支払い方法：不明";
                                    }
                                    ?>
                                </p>

                                <?php if ($paymentmethod === "クレジット" || $paymentmethod === "スマホ決済") : ?>
                                    <p>
                                        <?php
                                        //クレジット、スマホ決済の詳細出力
                                        if ($paymentmethod === "クレジット") {
                                            if ($credit !== null) {
                                                echo "カード種類：" . h($credit);
                                            } else {
                                                echo "カード種類：不明";
                                            }
                                        } else if ($paymentmethod === "スマホ決済") {
                                            if ($qr !== null) {
                                                echo "スマホ決済種類：" . h($qr);
                                            } else {
                                                echo "スマホ決済種類：不明";
                                            }
                                        }
                                        ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="u-flex-box p-sp-data-box__button">
                                <form action="./record-edit.php" method="post">
                                    <input type="hidden" name="record_id" value="<?php echo h($id); ?>">
                                    <input type="submit" class="c-button c-button--bg-green edit" id="" value="編 集">
                                </form>
                                <a class="c-button c-button--bg-red delete" id="delete<?php echo h($id); ?>Group" href="./delete.php?id=<?php echo h($id); ?>;" onclick="deleteConfirm('<?php echo h($title); ?>', 'delete<?php echo h($id); ?>Group');">削 除</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endfor; ?>
        </div>
        <?php
        $stmt_dataoutput = $db->prepare($sql_dataoutput . $add_where_month . $add_order . $add_limit);
        $stmt_dataoutput->bind_param("siii", $month_param, $user["id"], $page_param, $max_view);
        sql_check($stmt_dataoutput, $db);

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
            $name
        ); ?>

        <div id="allView" class="p-sp-data-box__allview" style="height: 210px;">
            <?php while ($stmt_dataoutput->fetch()) : ?>
                <div class="p-sp-data-box item<?php echo h($id); ?>">
                    <div class="u-flex-box p-sp-data-box__overview <?php echo $memo !== "" ? "hasmemo" : ""; ?>" style="gap: 10px;">
                        <p style="margin: auto 0;"><?php echo h($name); ?>　</p>
                        <p><?php echo h($title); ?>
                            <span style="display: inline-block;">
                                <?php
                                if ($type === 0 && $spending_category !== null) {
                                    echo "(" . h($spending_category) . ")";
                                } else if ($type === 1 && $income_category !== null) {
                                    echo "(" . h($income_category) . ")";
                                } else {
                                    echo "(カテゴリー不明)";
                                }
                                ?>
                                <i class="fa-regular fa-message" onclick="showMemo('<?php echo h($memo); ?>');" style="position: static; display: inline;"></i>
                            </span>
                        </p>
                        <p class="<?php echo $type === 0 ? "text-red" : "text-blue" ?>">
                            <?php echo h($type) === "0" ? "-￥" . number_format($amount) : ""; ?>
                            <?php echo h($type) === "1" ? "+￥" . number_format($amount) : ""; ?>
                        </p>
                    </div>
                    <div class="p-sp-data-box__detail">
                        <p><?php echo date("Y/m/d", strtotime($date)); ?></p>
                        <p>
                            <?php
                            //支払い方法の出力
                            if ($type === 0 && $paymentmethod !== null) {
                                echo "支払い方法：" . h($paymentmethod);
                            } else if ($type === 1) {
                                echo "";
                            } else {
                                echo "支払い方法：不明";
                            }
                            ?>
                        </p>

                        <?php if ($paymentmethod === "クレジット" || $paymentmethod === "スマホ決済") : ?>
                            <p>
                                <?php
                                //クレジット、スマホ決済の詳細出力
                                if ($paymentmethod === "クレジット") {
                                    if ($credit !== null) {
                                        echo "カード種類：" . h($credit);
                                    } else {
                                        echo "カード種類：不明";
                                    }
                                } else if ($paymentmethod === "スマホ決済") {
                                    if ($qr !== null) {
                                        echo "スマホ決済種類：" . h($qr);
                                    } else {
                                        echo "スマホ決済種類：不明";
                                    }
                                }
                                ?>
                            </p>
                        <?php endif; ?>

                    </div>
                    <div class="u-flex-box p-sp-data-box__button">
                        <form action="./record-edit.php" method="post">
                            <input type="hidden" name="record_id" value="<?php echo h($id); ?>">
                            <input type="submit" class="c-button c-button--bg-green edit" id="" value="編 集">
                        </form>
                        <a class="c-button c-button--bg-red delete" id="delete<?php echo h($id); ?>sp" href="./delete.php?id=<?php echo h($id); ?>&from=index" onclick="deleteConfirm('<?php echo h($title); ?>', 'delete<?php echo h($id); ?>sp');">削 除</a>
                    </div>
                </div>
            <?php endwhile; ?>
            <!-- //収支データ出力 -->
            <div class="p-pagenation">
                <?php if ($page_id > 1 && isset($_GET["search_month"])) : ?>
                    <a class="prev" href="./index.php?search_month=<?php echo date("Y-m", $base_date); ?>&page_id=<?php echo ($page_id - 1); ?>#data-table_sp">前へ</a>
                <?php elseif ($page_id > 1 && isset($_GET["ym"])) : ?>
                    <a class="prev" href="./index.php?ym=<?php echo $ym; ?>&page_id=<?php echo ($page_id - 1); ?>#data-table_sp">前へ</a>
                <?php elseif ($page_id > 1 && !isset($_GET["ym"]) && !isset($_GET["search_month"])) : ?>
                    <a class="prev" href="./index.php?page_id=<?php echo ($page_id - 1); ?>#data-table_sp">前へ</a>
                <?php endif; ?>
                <?php if ($page_id < $pages && isset($_GET["search_month"])) : ?>
                    <a class="next" href="./index.php?search_month=<?php echo date("Y-m", $base_date); ?>&page_id=<?php echo ($page_id + 1); ?>#data-table_sp">次へ</a>
                <?php elseif ($page_id < $pages && isset($_GET["ym"])) : ?>
                    <a class="next" href="./index.php?ym=<?php echo $ym; ?>&page_id=<?php echo ($page_id + 1); ?>#data-table_sp">次へ</a>
                <?php elseif ($page_id < $pages && !isset($_GET["ym"]) && !isset($_GET["search_month"])) : ?>
                    <a class="next" href="./index.php?page_id=<?php echo ($page_id + 1); ?>#data-table_sp">次へ</a>
                <?php endif; ?>
            </div>
        </div>

    <?php else : ?>
        <div class="p-sp-data-box nodata">
            <p>データがありません</p>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php if ($select === "child") : ?>
    <?php
    //月データ日付まとめで表示
    $date_list = array(); //データがある日付を配列で入れる箱を用意
    $count_list = array(); //各日付されているデータ数を配列で入れる箱を用意
    $week_list = ["日", "月", "火", "水", "木", "金", "土"]; //日本語曜日配列の用意

    //日付でグループ化したデータを抽出
    $sql = "SELECT COUNT(*), date FROM records WHERE child_id = ? AND date LIKE ?";
    if (isset($_POST["detail-search"])) :
        $add_sql_title = add_sql_title($filtering_title);
        $sql .= $add_sql_title;
        for ($i = 0; $i < count($filter_column); $i++) :
            $add_sql = add_sql_item($filter_column[$i], $filter_value[$i]);
            $sql .= $add_sql;
        endfor;
    endif;
    $sql .= " GROUP BY date ORDER BY date";

    $stmt = $db->prepare($sql);
    $stmt->bind_param("is", $user["id"], $month_param);
    $stmt->execute();
    $stmt->store_result();
    $count = $stmt->num_rows();
    $stmt->bind_result($count_item, $date_item);
    while ($stmt->fetch()) :
        $date_list[] = $date_item; //日付データを取り出し配列に入れる
        $count_list[] = $count_item; //各日付に登録されているデータ数を取り出し配列に入れる
    endwhile;
    ?>
    <?php if ($count > 0) : ?>
        <div id="groupView" class="p-sp-data-box__groupview hide" style="height: 200px;">
            <?php
            for ($i = 0; $i < count($date_list); $i++) :
                $search_date = $date_list[$i];
                $create_week = date("w", strtotime($search_date));
                $day_of_week = $week_list[$create_week];
            ?>
                <div class="p-toggledate-tab js-toggle" id="date<?php echo h($search_date); ?>" onclick="onClickDataBanner('<?php echo $search_date; ?>');">
                    <p class="date">
                        <?php echo date("n月j日", strtotime($date_list[$i])); ?>
                        <span class="day-of-week">(<?php echo ($day_of_week); ?>)</span>
                    </p>
                    <p class="count">(<?php echo h($count_list[$i]); ?>件 )</p>
                </div>
                <div class="p-sp-data-box__frame hide" id="item<?php echo $search_date; ?>">
                    <?php
                    $stmt_dataoutput = $db->prepare($sql_dataoutput . $add_where_date);
                    $stmt_dataoutput->bind_param("si", $search_date, $user["id"]);
                    sql_check($stmt_dataoutput, $db);
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
                        <div class="p-sp-data-box item<?php echo h($id); ?>">
                            <div class="u-flex-box p-sp-data-box__overview <?php echo $memo !== "" ? "hasmemo" : ""; ?>">
                                <p> <?php echo h($title); ?>
                                    <span>
                                        <?php
                                        if ($type === 0 && $spending_category !== null) {
                                            echo "(" . h($spending_category) . ")";
                                        } else if ($type === 1 && $income_category !== null) {
                                            echo "(" . h($income_category) . ")";
                                        } else {
                                            echo "(カテゴリー不明)";
                                        }
                                        ?>
                                        <i class="fa-regular fa-message" onclick="showMemo('<?php echo h($memo); ?>')"></i> </span>
                                </p>
                                <p class="<?php echo $type === 0 ? "text-red" : "text-blue" ?>">
                                    <?php echo h($type) === "0" ? "-￥" . number_format($amount) : ""; ?>
                                    <?php echo h($type) === "1" ? "+￥" . number_format($amount) : ""; ?>
                                </p>
                            </div>
                            <div class="p-sp-data-box__detail">
                                <p>
                                    <?php
                                    //支払い方法の出力
                                    if ($type === 0 && $paymentmethod !== null) {
                                        echo "支払い方法：" . h($paymentmethod);
                                    } else if ($type === 1) {
                                        echo "";
                                    } else {
                                        echo "支払い方法：不明";
                                    }
                                    ?>
                                </p>

                                <?php if ($paymentmethod === "クレジット" || $paymentmethod === "スマホ決済") : ?>
                                    <p>
                                        <?php
                                        //クレジット、スマホ決済の詳細出力
                                        if ($paymentmethod === "クレジット") {
                                            if ($credit !== null) {
                                                echo "カード種類：" . h($credit);
                                            } else {
                                                echo "カード種類：不明";
                                            }
                                        } else if ($paymentmethod === "スマホ決済") {
                                            if ($qr !== null) {
                                                echo "スマホ決済種類：" . h($qr);
                                            } else {
                                                echo "スマホ決済種類：不明";
                                            }
                                        }
                                        ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="u-flex-box p-sp-data-box__button">
                                <form action="./record-edit.php" method="post">
                                    <input type="hidden" name="record_id" value="<?php echo h($id); ?>">
                                    <input type="submit" class="c-button c-button--bg-green edit" id="" value="編 集">
                                </form>
                                <a class="c-button c-button--bg-red delete" id="delete<?php echo h($id); ?>Group" href="./delete.php?id=<?php echo h($id); ?>;" onclick="deleteConfirm('<?php echo h($title); ?>', 'delete<?php echo h($id); ?>Group');">削 除</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endfor; ?>
        </div>
        <?php
        $stmt_dataoutput = $db->prepare($sql_dataoutput . $add_where_month . $add_order . $add_limit);
        $stmt_dataoutput->bind_param("siii", $month_param, $user["id"], $page_param, $max_view);
        sql_check($stmt_dataoutput, $db);

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
        ); ?>

        <div id="allView" class="p-sp-data-box__allview" style="height: 210px;">
            <?php while ($stmt_dataoutput->fetch()) : ?>
                <div class="p-sp-data-box item<?php echo h($id); ?>">
                    <div class="u-flex-box p-sp-data-box__overview <?php echo $memo !== "" ? "hasmemo" : ""; ?>" style="gap: 10px;">
                        <p><?php echo h($title); ?>
                            <span>
                                <?php
                                if ($type === 0 && $spending_category !== null) {
                                    echo "(" . h($spending_category) . ")";
                                } else if ($type === 1 && $income_category !== null) {
                                    echo "(" . h($income_category) . ")";
                                } else {
                                    echo "(カテゴリー不明)";
                                }
                                ?>
                                <i class="fa-regular fa-message" onclick="showMemo('<?php echo h($memo); ?>');"></i> </span>
                        </p>
                        <p class="<?php echo $type === 0 ? "text-red" : "text-blue" ?>">
                            <?php echo h($type) === "0" ? "-￥" . number_format($amount) : ""; ?>
                            <?php echo h($type) === "1" ? "+￥" . number_format($amount) : ""; ?>
                        </p>
                    </div>
                    <div class="p-sp-data-box__detail">
                        <p><?php echo date("Y/m/d", strtotime($date)); ?></p>
                        <p>
                            <?php
                            //支払い方法の出力
                            if ($type === 0 && $paymentmethod !== null) {
                                echo "支払い方法：" . h($paymentmethod);
                            } else if ($type === 1) {
                                echo "";
                            } else {
                                echo "支払い方法：不明";
                            }
                            ?>
                        </p>

                        <?php if ($paymentmethod === "クレジット" || $paymentmethod === "スマホ決済") : ?>
                            <p>
                                <?php
                                //クレジット、スマホ決済の詳細出力
                                if ($paymentmethod === "クレジット") {
                                    if ($credit !== null) {
                                        echo "カード種類：" . h($credit);
                                    } else {
                                        echo "カード種類：不明";
                                    }
                                } else if ($paymentmethod === "スマホ決済") {
                                    if ($qr !== null) {
                                        echo "スマホ決済種類：" . h($qr);
                                    } else {
                                        echo "スマホ決済種類：不明";
                                    }
                                }
                                ?>
                            </p>
                        <?php endif; ?>

                    </div>
                    <div class="u-flex-box p-sp-data-box__button">
                        <form action="./record-edit.php" method="post">
                            <input type="hidden" name="record_id" value="<?php echo h($id); ?>">
                            <input type="submit" class="c-button c-button--bg-green edit" id="" value="編 集">
                        </form>
                        <a class="c-button c-button--bg-red delete" id="delete<?php echo h($id); ?>sp" href="./delete.php?id=<?php echo h($id); ?>&from=index" onclick="deleteConfirm('<?php echo h($title); ?>', 'delete<?php echo h($id); ?>sp');">削 除</a>
                    </div>
                </div>
                <!-- //収支データ出力 -->
            <?php endwhile; ?>

            <div class="p-pagenation">
                <?php if ($page_id > 1 && isset($_GET["search_month"])) : ?>
                    <a class="prev" href="./index.php?search_month=<?php echo date("Y-m", $base_date); ?>&page_id=<?php echo ($page_id - 1); ?>#data-table">前へ</a>
                <?php elseif ($page_id > 1 && isset($_GET["ym"])) : ?>
                    <a class="prev" href="./index.php?ym=<?php echo $ym; ?>&page_id=<?php echo ($page_id - 1); ?>#data-table_sp">前へ</a>
                <?php elseif ($page_id > 1 && !isset($_GET["ym"]) && !isset($_GET["search_month"])) : ?>
                    <!-- <a class="prev" href="./index.php?page_id=<?php echo ($page_id - 1); ?>#data-table_sp">前へ</a> -->
                    <a class="prev" href="./index.php?page_id=<?php echo ($page_id - 1); ?>#data-table_sp">前へ</a>
                <?php endif; ?>
                <?php if ($page_id < $pages && isset($_GET["search_month"])) : ?>
                    <a class="prev" href="./index.php?search_month=<?php echo date("Y-m", $base_date); ?>&page_id=<?php echo ($page_id - 1); ?>#data-table">前へ</a>
                <?php elseif ($page_id < $pages && isset($_GET["ym"])) : ?>
                    <a class="next" href="./index.php?ym=<?php echo $ym; ?>&page_id=<?php echo ($page_id + 1); ?>#data-table_sp">次へ</a>
                <?php elseif ($page_id < $pages && !isset($_GET["ym"]) && !isset($_GET["search_month"])) : ?>
                    <!-- <a class="next" href="./index.php?page_id=<?php echo ($page_id + 1); ?>#data-table_sp">次へ</a> -->
                    <a class="next" href="./index.php?page_id=<?php echo ($page_id + 1); ?>#data-table_sp">次へ</a>
                <?php endif; ?>
            </div>
        </div>

    <?php else : ?>
        <div class="p-sp-data-box nodata">
            <p>データがありません</p>
        </div>
    <?php endif; ?>
<?php endif; ?>