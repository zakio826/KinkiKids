<!-- カレンダー日別収支詳細表示 -->
<?php if (isset($_GET["detail"])) : ?>
    <section class="p-section p-section__full-screen" id="detailModalBox">
        <div class="p-detail">
            <div class="p-detail-box">
                <?php
                $param_date = $_GET["detail"];
                $title_date = date("n月j日", strtotime($param_date));
                ?>

                <!--タイトル出力-->
                <p class="p-detail-box__title">
                    <?php echo $title_date; ?>
                </p>

                <!--詳細データ抽出-->
                <h2>買い物</h2>
                <div class="p-detail-box_list">
                    <?php
                    if ($select === "adult") {
                        $sql = "SELECT records.id, records.date, records.title, records.amount, spending_category.name, income_category.name, records.type, payment_method.name, creditcard.name, qr.name, records.memo
                                FROM records
                                LEFT JOIN spending_category ON records.spending_category = spending_category.id
                                LEFT JOIN income_category ON records.income_category = income_category.id
                                LEFT JOIN payment_method ON records.payment_method = payment_method.id
                                LEFT JOIN creditcard ON records.creditcard = creditcard.id
                                LEFT JOIN qr ON records.qr = qr.id
                                WHERE records.date=? AND records.user_id = ?";
                        $stmt = $db->prepare($sql);
                        $stmt->bind_param("si", $param_date, $user["id"]);
                    } elseif ($select === "child") {
                        $sql = "SELECT records.id, records.date, records.title, records.amount, spending_category.name, income_category.name, records.type, payment_method.name, creditcard.name, qr.name, records.memo
                                FROM records
                                LEFT JOIN spending_category ON records.spending_category = spending_category.id
                                LEFT JOIN income_category ON records.income_category = income_category.id
                                LEFT JOIN payment_method ON records.payment_method = payment_method.id
                                LEFT JOIN creditcard ON records.creditcard = creditcard.id
                                LEFT JOIN qr ON records.qr = qr.id
                                WHERE records.date = ? AND records.child_id = ?";
                        $stmt = $db->prepare($sql);
                        $stmt->bind_param("si", $param_date, $user["id"]);
                    }
                    sql_check($stmt, $db);

                    $stmt->store_result();
                    $count = $stmt->num_rows();

                    $stmt->bind_result($id, $date, $title, $amount, $spending_cat, $income_cat, $type, $payment_method, $credit, $qr, $memo);

                    if ($count > 0) :
                        while ($stmt->fetch()) :
                    ?>
                            <div class="p-detail-box__content">
                                <div class="outline">
                                    <p>
                                        <?php echo $title; ?>
                                        <span>
                                            <?php
                                            if ($type === 0 && $spending_cat !== null) {
                                                echo "(" . $spending_cat . ")";
                                            } elseif ($type === 1 && $income_cat !== null) {
                                                echo "(" . $income_cat . ")";
                                            } else {
                                                echo "(不明)";
                                            }
                                            ?>
                                        </span>
                                    </p>
                                    <p class="<?php echo $type === 0 ? 'text-red' : 'text-blue'; ?>"><?php echo $type === 0 ? "-￥" . number_format($amount) : "+￥" . number_format($amount); ?></p>
                                </div>
                                <?php if ($type === 0) : ?>
                                    <p class="detail">
                                        <?php
                                        echo ($payment_method != "") ? $payment_method : "現金";
                                        if ($credit !== null || $qr !== null) {
                                            echo "/" . $credit . $qr;
                                        }
                                        ?>
                                    </p>
                                <?php endif; ?>

                                <div class="p-detail-box__editbutton">
                                    <form action="./record-edit.php" method="POST">
                                        <input type="hidden" name="record_id" value="<?php echo h($id); ?>">
                                        <input type="submit" class="c-button c-button--bg-green edit fas" id="" value="編集">
                                    </form>
                                    <a class="c-button c-button--bg-red delete" id="delete<?php echo h($id); ?>" href="./delete.php?id=<?php echo h($id); ?>&from=index" onclick="deleteConfirm('<?php echo h($title); ?>','delete<?php echo h($id); ?>');"> 削除 </a>
                                </div>
                            </div>
                        <?php endwhile; ?>

                    <?php else : ?>
                        <div class="p-detail-box__content nodata">
                            <p>データなし</p>
                        </div>
                    <?php endif; ?>
                </div>

                <h2>お手伝い</h2>
                <div class="p-detail-box_list">
                    <?php
                    $points_col = [
                        "points.id" => "id",
                        "date" => "date",
                        "title" => "title",
                        "points.family_id" => "family_id",
                        "child_id" => "child_id",
                        "points.point" => "point",
                    ];
                    $points_join = [
                        "help" => "points.help_id = help.id",
                    ];
                    if ($select === "adult") {
                        $points_where = [
                            "points.family_id" => ["=", "i", $family_id],
                            "date" => ["=", "s", $param_date],
                        ];
                    } elseif ($select === "child") {
                        $points_where = [
                            "child_id" => ["=", "i", $user["id"]],
                            "date" => ["=", "s", $param_date],
                        ];
                    }

                    $points = select($db, $points_col, "points", wheres: $points_where, joins: $points_join);
                    $count = count($points);

                    if ($count > 0) :
                        while ($row = current($points)) :
                    ?>
                            <div class="p-detail-box__content">
                                <div class="outline">
                                    <p>
                                        <?php echo $row["title"]; ?>
                                        <span>
                                        </span>
                                    </p>
                                    <p class=""><?php echo $row["point"]; ?>pt</p>
                                </div>
                            </div>
                        <?php
                            next($points);
                        endwhile;
                        ?>
                    <?php else : ?>
                        <div class="p-detail-box__content nodata">
                            <p>データなし</p>
                        </div>
                    <?php endif; ?>
                </div>

                <form action="" method="POST">
                    <input type="hidden" name="specific_register_date" value="<?php echo $param_date; ?>">
                    <input type="submit" name="specific_register" value="追加" id="detailModalAdd" class="c-button c-button--bg-blue add" onclick="onClickDetailModalAdd();">
                </form>

                <?php
                if (isset($_GET["ym"])) :
                    $ym = $_GET["ym"];
                endif;

                if (isset($_GET["page_id"])) :
                    $page_id = $_GET["page_id"];
                endif;

                if (isset($_GET["ym"]) && isset($_GET["page_id"])) :
                    $detail_ok_link = "./index.php?ym=" . $ym . "&page_id=" . $page_id;
                elseif (isset($_GET["ym"])) :
                    $detail_ok_link = "./index.php?ym=" . $ym;
                elseif (isset($_GET["page_id"])) :
                    $detail_ok_link = "./index.php?page_id=" . $page_id;
                else :
                    $detail_ok_link = "./index.php";
                endif;
                ?>
            </div>
            <a class="back" href="<?php echo $detail_ok_link; ?>"><img src="./img/back.png"></a>
        </div>
    </section>
<?php endif; ?>
<!-- カレンダー日別収支詳細表示 -->