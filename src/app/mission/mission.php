<?php
include_once("complete_mission.php");
include_once("errand_list.php");
include_once("errand_operation.php");
include_once("trade_exchange.php");

$exist = null;
?>
<section id="mission" class="p-section p-section__mission js-switch-content fade-in hide" data-tab="tab-5">
    <?php if ($select === "adult") : ?>
        <div class="mission mission_switch-add">
            <!-- <h3>ミッションの追加</h3> -->
            <form action="" method="post" class="u-flex-box mission-add">
                <input type="hidden" name="add" value="mission_add">
                <div class="p-form__flex-input mission-add__overview">
                    <p style="width: 20%">お手伝い名</p>
                    <input type="text" name="mission_title">
                </div>
                <div class="p-form__flex-input mission-add__overview">
                    <p style="width: 20%">ポイント数</p>
                    <input type="number" name="point">
                </div>
                <input type="submit" name="mission_add" class="c-button c-button--bg-blue" value="追加" style="width: 100%; margin: 20px 0;">
            </form>
        </div>

        <div class="mission mission_switch-add hide">
            <!-- <h3>ミッションの追加</h3> -->
            <form action="" method="post" class="u-flex-box mission-add">
                <!-- <input type="hidden" name="add" value="exchange_add"> -->
                <div class="p-form__flex-input mission-add__overview">
                    <p style="width: 20%">交換物</p>
                    <input type="text" name="exchange_title">
                </div>
                <div class="p-form__flex-input mission-add__overview">
                    <p style="width: 20%">ポイント数</p>
                    <input type="number" name="exchange_point">
                </div>
                <div class="p-form__flex-input mission-add__overview">
                    <p style="width: 20%">子ども</p>
                    <!-- <input type="number" name="exchange_child"> -->
                    <select name="exchange_child" style="background-color: #fff; border-color: #333; width: 75%; height: 29px;">
                        <?php
                        $cols = array("id", "name",);
                        $wheres = array("family_id" => ["=", "i", $family_id],);
                        $children = select($db, $cols, "child", wheres: $wheres);
                        ?>
                        <?php foreach ($children as $item) : ?>
                            <option value="<?php echo $item["id"]; ?>"><?php echo $item["name"]; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input type="submit" name="exchange_add" class="c-button c-button--bg-blue" value="追加" style="width: 100%; margin: 20px 0;">
            </form>
        </div>
    <?php endif; ?>

    <ul class="mission_switch" id="mission">
        <li class="mission_switch__item is-active" data-mission="mission-1">ミッション</li>
        <li class="mission_switch__item" data-mission="mission-2">ポイント交換</li>
    </ul>

    <div class="mission mission_switch-content">
        <div class="mission_list" id="mission_list_A">
            <?php if ($select == "child") : ?>
                <?php
                $errand_cols = array(
                    "number" => "id",
                    "id" => "parent",
                    "date" => "date",
                    "mission" => "errand",
                    "flag" => "flag",
                    "point" => "point",
                );
                $errand_wheres = array(
                    "id" => ["=", "i", $user["parent"]],
                    "date" => ["=", "s", $today],
                );

                $errand_result = select($line, $errand_cols, "ErrandMission", wheres: $errand_wheres);
                ?>

                <?php if (count($errand_result) > 0) : ?>
                    <?php for ($i = 0; $i < count($errand_result); $i++) :?>
                        <?php
                        $detail_url = $_SERVER["REQUEST_URI"] . "?errand_detail=" . $errand_result[$i]["id"];
                        ?>
                        
                        <a class="<?php echo $errand_result[$i]["flag"] !== 0 ? "link_disabled" : ""; ?>" href="<?php echo $detail_url; ?>">
                            <div class="mission-box errand<?php echo $errand_result[$i]["flag"] === 0 ? '' : ' complete' ?>">
                                <div class="u-flex-box mission-box__overview">
                                    <p class="name"><?php echo h($errand_result[$i]["errand"]) ?></p>
                                </div>
                                <span>報酬<p class="point"><?php echo $errand_result[$i]["point"]; ?>pt</p></span>
                            </div>
                        </a>
                    <?php endfor; ?>
                <?php endif; ?>

                <?php
                $cols = array(
                    "number",
                    "id",
                    "date",
                    "mission",
                    "flag",
                    "point",
                );
                $wheres = array(
                    "id" => ["=", "i", $user["parent"]],
                    // "flag" => ["=", "i", 0],
                    "date" => ["=", "s", $today],
                );
                $order = array(
                    // "order" => ["id", true],
                );

                $result = select($line, $cols, "EmergencyMission", wheres: $wheres, group_order: $order);
                ?>
                        
                <?php if (count($result) > 0) : ?>
                    <?php for ($i = 0; $i < count($result); $i++) : ?>
                        <div class="mission-box emergency<?php echo $result[$i]["flag"] === 0 ? '' : ' complete'; ?>"
                            data-id="<?php echo $result[$i]["id"]; ?>"
                            data-mission='{"mission_id": "<?php echo $result[$i]["number"]; ?>", "point": "<?php echo $result[$i]["point"]; ?>"}'>
                            <div class="u-flex-box mission-box__overview">
                                <p class="name"><?php echo h($result[$i]["mission"]); ?></p>
                            </div>
                            <span>報酬<p class="point"><?php echo $result[$i]["point"]; ?>pt</p></span>
                        </div>
                    <?php endfor; ?>
                <?php endif; ?>
            <?php endif; ?>

            <?php
            $rows = array();
            $i = 0;
            $date = new DateTime("now");
            $today = $date->format("Y-m-d");
            $input_time = $date->format("Y/m/d-H:i:s");

            $sql = "SELECT DISTINCT(help_id) FROM points WHERE family_id = ? AND date = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("is", $family_id, $today);
            sql_check($stmt, $db);
            $stmt->bind_result($help_id);
            
            for ($c = 0; $stmt->fetch(); $c++) {
                $rows[$c] = $help_id;
            }
            $stmt->close();

            $sql = "SELECT id, title, point FROM help WHERE family_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("i", $family_id);
            sql_check($stmt, $db);
            $stmt->bind_result($id, $title, $point);

            $stmt->store_result();
            $count = $stmt->num_rows();
                    ?>

            <?php if ($count > 0) : ?>
                <?php while ($stmt->fetch()) : ?>
                    <div class="mission-box item<?php echo h($id); ?><?php echo in_array($id, $rows) ? ' complete' : '' ?>"
                        data-id="<?php echo $id; ?>"
                        data-mission='{"id": "<?php echo $id; ?>", "title": "<?php echo $title; ?>", "point": "<?php echo $point; ?>", "today": "<?php echo $today; ?>", "family_id": "<?php echo $family_id; ?>", "input_time": "<?php echo $input_time; ?>"}'>
                        <div class="u-flex-box mission-box__overview" id="mission_backobject">
                            <p class="name" id="mozihiritu2_ti"><?php echo h($title) ?></p>
                        </div>
                        <span class="mozihiritu2">報酬<p class="point"><?php echo $point; ?>pt</p></span>
                    </div>
                    <?php $i++; ?>
                <?php endwhile; ?>
            <?php elseif (($select == "child" && count($errand_result) == 0) && count($result) == 0 && $count == 0) : ?>
                <div class="nodata"><p>データがありません</p></div>
            <?php endif; ?>

            <?php $stmt->close(); ?>
        </div>
    </div>

    <div class="mission mission_switch-content hide">
        <!-- <img src="./img/exchange.png"> -->
        <?php if ($select === "child") : ?>
            <div class="exchange">
                <p class="mozihiritu1">持ってるポイント:<?php echo $user["points"] ?>pt</p>
            </div>
        <?php endif; ?>
        <div class="mission_exchange" id="point_koukan1">
            <div class="mission_exchange__list" id="point_koukan_mozihiritu">
                <div class="mission_exchange__list-box item0" id="point_money_koukan1">
                    <div class="u-flex-box mission_exchange__list-box__overview">
                        <p class="name">お金に変える</p>
                        <?php if ($select == "child") : ?>
                            <button class="c-button c-button--bg-blue" id="point_koukan_btn1" onclick="location.href='.?trade'" <?php echo $user["points"] < 0 ? "disabled" : ""; ?>>交換</button>
                        <?php endif; ?>
                    </div>
                </div>

                <form method="post">
                    <?php
                    $date = new DateTime("now");
                    $today = $date->format("Y-m-d");
                    $input_time = $date->format("Y/m/d-H:i:s");

                    $cols = array(
                        "id",
                        "name",
                        "child_id",
                        "family_id",
                        "point",
                    );

                    $wheres = array(
                        "family_id" => ["=", "i", $family_id],
                    );

                    $result = select($db, $cols, "exchange", wheres: $wheres);
                                                ?>

                    <?php if (count($result) > 0) : ?>
                        <?php while ($row = current($result)) : ?>
                            <div class="mission_exchange__list-box item<?php echo h($row["id"]); ?>">
                                <div class="u-flex-box mission_exchange__list-box__overview">
                                    <p class="name"><?php echo h($row["name"]) ?></p>
                                    <p class="point"><?php echo $row["point"]; ?>pt</p>
                                    <?php if ($select == "child") : ?>
                                        <button class="c-button c-button--bg-blue" id="point_koukan_btn1" name="exchange" value="<?php echo $row["id"]; ?>" type="submit">交換</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php next($result); ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <div class="nodata" id="point_money_koukan2"><p>データがないよ</p></div>
                    <?php endif; ?>
                </form>
            </div>

            <style>
                #mission_list_A {
                    border-radius: 1.75rem;
                    background-size: 100%;
                    padding-left: 3vw;
                }
                #point_koukan1 {
                    height: 100vw;
                    width: 85vw;
                    position: relative;
                }
                #point_money_koukan1 {
                    /* position: absolute; */
                    top: 10%;
                    left: 20%;
                }
                #point_money_koukan2 {
                    /* position: absolute; */
                    top: 30%;
                    left: 30%;
                }
                #point_koukan_mozihiritu {
                    font-size: 4.5vw;
                    overflow-y: initial;
                }
                .mozihiritu1 {
                    font-size: 4vw;
                }
                .mozihiritu2 {
                    font-size: 4.1vw;
                }
                #mozihiritu2_ti {
                    font-size: 4vw;
                }
                #mozihiritu1 {
                    font-size: 3vw;
                }
                #point_koukan_btn1 {
                    font-size: 3vw;
                    padding: 0.1vw 1vw 0.3vw 1vw;
                    border-radius: 10%;
                    margin-bottom: 1vw;
                    /* margin-bottom: 7px; */
                    /* margin-left: 2vw; */
                }
                #mission_backobject {
                    position: relative;
                    background-size: initial;
                    background: initial;
                    background-color: #513a0e;
                }
                /* #mission_backobject::after {
                    position: absolute;
                    right: -40px;
                    border-left: 20px solid;
                    border-top: 20px solid transparent;
                    border-right: 20px solid transparent;
                    border-bottom: 20px solid transparent;
                } */
            </style>
        </div>
    </div>
</section>