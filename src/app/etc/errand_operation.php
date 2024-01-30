<!-- カレンダー日別収支詳細表示 -->
<?php if (isset($_GET["to_goal"])) : ?>
    <section class="p-section p-section__full-screen" id="detailModalBox">
        <div class="p-detail">
            <div class="p-detail-box">
                <?php
                $goal_col = [
                    "wish",
                    "price",
                    "date",
                ];

                $goal_where = [
                    "child_id" => ["=", "i", $user["id"]],
                ];

                $goal_result = select($db, $goal_col, "wish_list", wheres:$goal_where, limits:1);
                ?>

                <!--タイトル出力-->
                <p class="p-detail-box__title">
                    <?php //echo $title_date;
                    ?>
                </p>

                <!--詳細データ抽出-->
                <div class="p-detail-box_list">
                    <div class="p-detail-box__content">
                        <div class="">
                            <p><?php echo $goal_result[0]["wish"]; ?></p>
                            <p>期限まで<?php echo $goal_result[0]["date"]; ?>日</p>
                            <p>ほしいものまであと<?php echo $goal_result[0]["price"]; ?>円</p>
                            <!-- <label for="file"></label> -->
                            <progress id="file" max="100" value="70">70%</progress>
                        </div>
                    </div>
                </div>

                <form action="" method="POST">
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