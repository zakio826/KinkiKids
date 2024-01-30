<!-- カレンダー日別収支詳細表示 -->
<?php if (isset($_GET["trade"])) : ?>
    <section class="p-section p-section__full-screen" id="detailModalBox">
        <div class="p-detail">
            <div class="p-detail-box">
                <?php
                $TRADE_RATE = 1.1;

                $goal_col = [
                    "wish",
                    "price",
                    "date",
                ];

                $goal_where = [
                    "child_id" => ["=", "i", $user["id"]],
                ];

                $goal_result = select($db, $goal_col, "wish_list", wheres: $goal_where, limits: 1);
                ?>

                <!--タイトル出力-->
                <p class="p-detail-box__title">
                    お金と交換
                </p>

                <!--詳細データ抽出-->
                <form action="" method="POST">
                    <div class="p-detail-box_list">
                        <div class="p-detail-box__content">
                            <div class="">
                                <input type="hidden" name="trade_rate" id="trade_rate" value="<?php echo $TRADE_RATE; ?>">
                                <span>
                                    <input onchange="tradeMoney();" type="number" name="trade_money" id="trade_money" value="0">円
                                </span>
                                <p>使うポイント
                                    <input type="hidden" name="trade_point" id="trade_point" class="trade_point">
                                    <span class="trade_point">0pt</span>
                                </p>
                                <span><input type="radio" name="trade_type" value="cashing" id="" checked>お金をもらう</span>
                                <span><input type="radio" name="trade_type" value="goal" id="">ほしい物に使う</span>
                            </div>
                        </div>
                    </div>

                    <input type="submit" name="trade" value="追加" id="trade" class="c-button c-button--bg-blue add" disabled>
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