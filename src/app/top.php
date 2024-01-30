<!-- ホーム画面 -->
<section class="p-section p-section__top js-switch-content fade-in" data-tab="tab-1" id="data-table">
<style>
    #tetudai_mokuhyo {
        height: initial;
        flex-direction: column;
        flex-wrap: wrap;
    }
    #mokuhyou_top1 {
        background-color: #f8f8e1;
        padding: 10px 5px 10px 5px;
        border-radius: 2.5rem;
        width: 70%;
        position: relative;
        /* display: unset; */
        border: black solid 1px;
        /* border-bottom: #e5e525 solid 3px; */
        /* border-right: #e5e525 solid 3px; */
        margin-top: 40px;
        /* top: 20%; */
    }
    .mokuhyou_top2 {
        border-top: 1px solid orange;
        background-color: rgb(232, 243, 131);
    }
    .mokuhyou_top3 {
        margin-top: 7px;
    }
    #mokuhyou_top4 {
        margin-bottom: -100px;
    }

    #tetudai_point_top1 {
        white-space: initial;
        /* white-space: pre; */
    }
    #tetudai_point_top2 {
        margin: 2rem 0;
    }
    /* #home_top_yoko {
        flex-wrap: wrap;
    } */
</style>
    <div <?php echo $select === "child" ? "class='top_upper'" : "" ?> id="tetudai_mokuhyo">
        <div class="children_detail" id="tetudai_point_top2">

            <h3>お手伝いポイント</h3>
            <?php
            $month_param = $search_month . "%";
            $date = new DateTime("now");
            $today = $date->format("Y-m-d");

            if ($select === "adult") :
                for ($i = 0; $i < count($user["child"]); $i++) :
                    // $sql = "SELECT SUM(point), (SELECT first_date FROM child WHERE id = ? LIMIT 1) as first_date FROM points WHERE child_id = ? LIMIT 1";
                    // $stmt = $db->prepare($sql);
                    // $stmt->bind_param("ii", $user["child"][$i]["id"], $user["child"][$i]["id"]);

                    $sql = "SELECT points, first_date FROM child WHERE id = ? LIMIT 1";
                    $stmt = $db->prepare($sql);
                    $stmt->bind_param("i", $user["child"][$i]["id"]);
                    sql_check($stmt, $db);
                    $stmt->bind_result($help_point, $first);
                    $stmt->fetch();
                    $stmt->close();

                    $first = new DateTime($first);
                    $total_login = $date->diff($first);
                    ?>
                    <div class="children_detail-box item<?php echo h($user["child"][$i]["id"]); ?>" data-select="adult">
                        <div class="u-flex-box children_detail-box__overview">
                            <p class="name">
                                <?php echo h($user["child"][$i]["name"]) ?>
                                <span>通算ログイン:
                                    <?php echo $total_login->format("%a"); ?> 日
                                </span>
                            </p>
                            <p class="point">
                                <?php echo h($help_point) ?> pt
                            </p>
                        </div>

                        <div class="indicator">
                            <?php
                            $sql = "SELECT
                                (SELECT SUM(amount) FROM records WHERE type = 1 AND child_id = ? AND date LIKE ?) AS income,
                                (SELECT SUM(amount) FROM records WHERE type = 0 AND child_id = ? AND date LIKE ?) AS spending
                                FROM records
                                WHERE child_id = ? AND date LIKE ? LIMIT 1";
                            $stmt = $db->prepare($sql);
                            $stmt->bind_param("isisis", $user["child"][$i]["id"], $month_param, $user["child"][$i]["id"], $month_param, $user["child"][$i]["id"], $month_param);
                            sql_check($stmt, $db);
                            $stmt->bind_result($income, $spending);
                            $stmt->fetch();
                            ?>
                            <div class="file_amount">
                                <label for="file">残金</label>
                                <label for="file" class="this_month">
                                    <?php echo $savings - $spending; ?>/
                                    <?php echo $savings; ?>円
                                </label>
                            </div>
                            <progress id="file" max="<?php echo $savings ?>" value="<?php echo $spending ?>"></progress>
                        </div>
                        <!-- <div class="u-flex-box p-sp-data-box__button">
                        <form action="./record-edit.php" method="post">
                            <input type="hidden" name="record_id" value="<?php //echo h($id);
                                                                            ?>">
                            <input type="submit" class="c-button c-button--bg-green edit" id="" value="編 集">
                        </form>
                        <a class="c-button c-button--bg-red delete" id="delete<?php //echo h($id);
                                                                                ?>sp" href='./delete.php?id=<?php echo h($id); ?>&from=index' onclick="deleteConfirm('<?php echo h($title); ?>', 'delete<?php echo h($id); ?>sp');">削 除</a>
                    </div> -->
                    </div>

            <?php
                    $stmt->close();
                endfor;
            endif;
            ?>

            <?php
            if ($select === "child") :
                // $sql = "SELECT SUM(point), (SELECT first_date FROM child WHERE id = ? LIMIT 1) as first_date FROM points WHERE child_id = ? LIMIT 1";
                $sql = "SELECT points, first_date FROM child WHERE id = ? LIMIT 1";
                $stmt = $db->prepare($sql);
                // $stmt->bind_param("ii", $child_id, $child_id);
                $stmt->bind_param("i", $user["id"]);
                sql_check($stmt, $db);
                $stmt->bind_result($help_point, $first);
                $stmt->fetch();
                $stmt->close();

                // echo strtotime($date) . " : " . strtotime($first) . "<br>";
                $first = new DateTime($first);
                $total_login = $date->diff($first);
            ?>
                <div class="children_detail-box item<?php echo h($user["id"]); ?>" data-select="child">
                    <div class="u-flex-box children_detail-box__overview">
                        <p class="name"><?php echo h($user["name"]) ?>
                            <span>連続ログイン: <?php echo $total_login->format("%a"); ?> 日</span>
                        </p>
                        <p class="point"><?php echo h($help_point) ?> pt</p>
                    </div>

                    <div class="indicator">
                        <?php
                        $sql = "SELECT
                                (SELECT SUM(amount) FROM records WHERE type = 1 AND child_id = ? AND date LIKE ?) AS income,
                                (SELECT SUM(amount) FROM records WHERE type = 0 AND child_id = ? AND date LIKE ?) AS spending
                                FROM records
                                WHERE child_id = ? AND date LIKE ? LIMIT 1";
                        $stmt = $db->prepare($sql);
                        $stmt->bind_param("isisis", $user["id"], $month_param, $user["id"], $month_param, $user["id"], $month_param);
                        sql_check($stmt, $db);
                        $stmt->bind_result($income, $spending);
                        $stmt->fetch();
                        ?>
                        <div class="file_amount">
                            <label for="file">のこり</label>
                            <label for="file" class="this_month"><?php echo $savings; ?>円</label>
                        </div>
                        <progress id="file" max="<?php echo $savings ?>" value="<?php echo $spending ?>"></progress>
                    </div>
                </div>
            <?php
                $stmt->close();
            endif;
            ?>
        </div>

        <?php if ($select === "child") : ?>
            <div class="goal" id="mokuhyou_top4">
                <a href="?goal">
                <!-- <img src="./img/cloud.png"> -->
                <div class="goal-box" id="mokuhyou_top1">
                    <h3>目標</h3>
                    <hr class="mokuhyou_top2">
                    <?php
                        $col = array(
                            "wish",
                            "price",
                            "date",
                            "input_date",
                            "savings",
                        );

                    $wheres = array(
                        "child_id" => ["=", "i", $user["id"]],
                    );

                    $order = array(
                        "order" => ["id", true],
                    );
                        $result = select($db, $col, "wish_list", wheres: $wheres, limits: 1, group_order: $order);
                    while ($row = current($result)) :
                            $input_date = new DateTime($row["input_date"]);
                            $until = $input_date->diff($date);
                    ?>
                            <p class="mokuhyou_top3">
                                <?php echo $row["wish"]; ?>
                            </p>
                            <p class="mokuhyou_top3">
                                <?php echo $row["price"]; ?>円
                            </p>
                    <?php
                        next($result);
                    endwhile;
                    ?>
                </div>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <div class="message">
        <div class="bg">
            <?php
            if ($select === "child") :
                $sql2 = "SELECT name FROM user WHERE id = ?";
                $stmt2 = $db->prepare($sql2);
                $stmt2->bind_param("i", $user["parent"]);
                $stmt2->execute();
                $stmt2->bind_result($parent_name);
                $stmt2->fetch();
                $stmt2->close();

                $sql = "SELECT txt, pid, child_id FROM LINEtxt WHERE pid = ? OR child_id = ?";
                $stmt = $line->prepare($sql);
                $stmt->bind_param("ii", $user["parent"], $user["id"]);
            elseif ($select === "adult") :
                $children = null;
                $child_id = array();
                $param = "i";

                for ($i = 0; $i < count($user["child"]); $i++) {
                    $child_id[] = $user["child"][$i]["id"];
                    $param .= "i";
                    $children .= " OR child_id = ?";
                }

                $sql = "SELECT txt, pid, child_id FROM LINEtxt WHERE pid = ?" . $children;
                $stmt = $line->prepare($sql);
                $stmt->bind_param($param, $user["id"], ...$child_id);
            endif;
            $stmt->execute();
            $stmt->bind_result($txt, $parent_id, $child_id);
            ?>

            <table>
                <?php while ($stmt->fetch()) : ?>
                    <tr>
                        <?php if ($parent_id !== 0) :
                            echo "<td class='name parent'>";
                            echo $select === "child" ? $parent_name : $user["first_name"];
                            echo "</td>";
                        endif;
                        if ($child_id !== 0) :
                            echo "<td class='name child'>";
                            if ($select === "child") {
                                echo $user["name"];
                            } else {
                                for ($i = 0; $i < count($user["child"]); $i++) {
                                    if ($child_id === $user["child"][$i]["id"]) {
                                        echo $user["child"][$i]["name"];
                                    }
                                }
                            }
                            echo "</td>";
                        endif;
                        ?>
                        <td class="msg"><?php echo $txt; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

    <?php if ($select === "child") : ?>
        <div class="send_message">
            <form action="" method="post">
                <input type="text" name="msg">
                <input type="submit" name="send">
            </form>
        </div>
    <?php endif; ?>

    <img src="./img/household.png" id="householdBtn" data-tab="4">

    <!-- <div class="mission">
        <h3>ポイントのやりとり</h3>
        <div class="mission_list">
            <?php
            $point_cols = [
                "points.id" => "id",
                "help_id" => "help_id",
                "date" => "date",
                "points.point" => "point",
                "title" => "title",
            ];
            $point_where = [
                "child_id" => ["=", "i", $user["id"]],
                "points.family_id" => ["=", "i", $family_id],
            ];
            $point_join = [
                "help" => "help.id = points.id",
            ];
            $point_order = [
                "order" => ["date", false],
            ];

            $point_result = select($db, $point_cols, "points", wheres: $point_where, joins: $point_join, group_order: $point_order);

            if (count($point_result) > 0) :
                while ($row = current($point_result)) : ?>
                    <div class="mission-box item<?php echo h($row["id"]); ?>" data-select="child">
                        <div class="u-flex-box mission-box__overview">
                            <p class="date"><?php echo h($row["date"]) ?></p>
                            <p class="name"><?php echo h($row["title"]) ?></p>
                            <p class="point"><?php echo $row["point"]; ?>pt</p>
                        </div>
                    </div>
                <?php
                    next($point_result);
                endwhile;
            else :
                ?>

    <?php //if ($select === "adult") :
    ?>
        <div class="mission">
            <h3>完了ミッション</h3>
            <?php
                // $sql = "SELECT points.id, help.title, points.point FROM points
                //                 LEFT JOIN help ON points.help_id = help.id
                //                 WHERE points.family_id = ? AND date = ?";
                // $stmt = $db->prepare($sql);
                // $stmt->bind_param("is", $family_id, $today);
                // sql_check($stmt, $db);
                // $stmt->bind_result($id, $title, $helping_point);
                // $stmt->store_result();
                // $count = $stmt->num_rows();

                // if ($count > 0) :
                while ($stmt->fetch()) : ?>
                    <div class="mission-box item<?php echo h($id); ?>" data-select="child">
                        <div class="u-flex-box mission-box__overview">
                            <p class="name"><?php echo h($title) ?></p>
                            <p class="point"><?php echo $helping_point; ?>pt</p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php //else :
            ?>
                <div class="nodata">
                    <p>データがありません</p>
                </div>
            <?php
            endif;
            $stmt->close();
            ?>
        </div>
    <?php //endif;
    ?>

    <!-- <div class="exchange" data-style="exchange">
        <h3>交換できるもの</h3>
        <?php
        $sql = "SELECT id, name, point FROM exchange WHERE family_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $family_id);
        sql_check($stmt, $db);
        $stmt->bind_result($id, $exchange_name, $point);
        $stmt->store_result();
        $count = $stmt->num_rows();

        if ($count > 0) :
            while ($stmt->fetch()) : ?>
                <div class="exchange-box item<?php echo h($id); ?>" data-select="child">
                    <div class="u-flex-box exchange-box__overview">
                        <p class="name"><?php echo h($exchange_name) ?></p>
                        <p class="point"><?php echo $point; ?>pt</p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="nodata">
                <p>データがありません</p>
            </div>
        <?php
        endif;
        $stmt->close();
        ?>
    </div> -->
</section>