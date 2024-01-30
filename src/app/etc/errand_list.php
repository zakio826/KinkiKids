<!-- カレンダー日別収支詳細表示 -->
<?php if (isset($_GET["errand_detail"])) : ?>
    <section class="p-section p-section__full-screen" id="detailModalBox">
        <div class="p-detail">
            <div class="p-detail-box">
                <?php
                $errand_id = $_GET["errand_detail"];
                ?>

                <!--タイトル出力-->
                <p class="p-detail-box__title">
                    お使いリスト
                </p>

                <!--詳細データ抽出-->
                <?php
                $errand_cols = [
                    "number",
                    "money"
                ];

                $errand_wheres = [
                    "number" => ["=", "i", $errand_id],
                ];

                $errand_result = select($line, $errand_cols, "ErrandMission", wheres: $errand_wheres);

                $list_cols = [
                    "number",
                    "id",
                    "goods",
                ];

                $list_wheres = [
                    "id" => ["=", "i", $errand_id],
                ];

                $list_result = select($line, $list_cols, "ErrandMissionList", wheres: $list_wheres);
                // }
                ?>
                <div class="p-detail-box__content">
                    <form method="POST">
                        <input type="hidden" name="errand_id" value="<?php echo $errand_id; ?>">
                        <input type="hidden" name="sum" id="errand_sum" value="<?php echo $sum; ?>">
                        <div class="outline">
                            <p>使えるお金</p>
                            <p class="text-blue">
                                <?php echo $errand_result[0]["money"]; ?>円
                            </p>
                        </div>
                        <?php while ($good = current($list_result)) : ?>
                            <div class="calc outline">
                                <p>
                                    <?php echo $good["goods"] ?>
                                    <!-- <input onblur="onChangeTotal(this.form, <?php echo $good['number']; ?>)" type="number" name="price[]" id="errand<?php echo $good["number"]; ?>" value="0"> -->
                                    <!-- <input onblur="submit(this.form)" type="number" name="price[]" id="errand<?php echo $good["number"]; ?>" value="0"> -->
                                    <!-- <input onchange="onChangeTotal(this.form, <?php echo $good['number']; ?>)" type="checkbox" name="bought_check<?php echo $good['number']; ?>"> -->
                                    <input type="number" class="errand" name="price[]" id="errand<?php echo $good["number"]; ?>" value="0">
                                    <input type="checkbox" class="bought_check" name="bought_check<?php echo $good['number']; ?>">
                                </p>
                            </div>
                        <?php
                            next($list_result);
                        endwhile;
                        ?>
                        <div class="detail">
                            <p>あまってるお金でお菓子を買おう!!!</p>
                            <p>あまったお金 <span id="surplus"><?php echo $errand_result[0]["money"]; ?></span>円</p>
                            <p>
                                おかしのお金 <input type="number" id="snack" value="0">円
                                <input type="checkbox" class="bought_check" name="bought_check<?php echo $good['number']; ?>">
                            </p>
                            <p>合計 <span id="bought_sum">0</span>円</p>
                        </div>
                        <!-- <input type="submit" name="errand_complete" value="完了" id="detailModalAdd" class="c-button c-button--bg-blue add" onclick="onClickErrandDetailModal()"> -->
                        <input type="submit" name="errand_complete" value="完了" id="detailModalAdd" class="c-button c-button--bg-blue add">
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
                <!-- <a class="back" href="<?php echo $detail_ok_link; ?>"><img src="./img/back.png"></a> -->
            </div>
    </section>
<?php endif; ?>
<!-- カレンダー日別収支詳細表示 -->