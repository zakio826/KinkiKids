<?php
require_once("../component/common/dbconnect.php");
include_once("../component/common/functions.php");
include_once("../component/common/session.php");
// -- スマホ画面のメニューバーのhtml 
include_once("../component/index/sp-tab2.php");

$page_title = "銀行";
include_once("./header.php");

if (isset($_POST["lending"]) && $_POST["lending"]) {
    // $done = 2;
    $id = filter_input(INPUT_POST, "lending", FILTER_SANITIZE_NUMBER_INT);
    // $sql = "UPDATE debt SET done = ? WHERE id = ?";
    // $stmt = $db->prepare($sql);
    // $stmt->bind_param("ii", $done, $id);

    // sql_check($stmt, $db);
    // exit();
    /////////////////////
    /////LINE処理

    $col = array(
        "id",
        "amount",
        "date",
        "repayment",
        "purpose",
        "reason",
"done",
        "division",
        "family_id",
        "child_id",
    );

    if ($select === "child") {
        $wheres = array(
            "id" => ["=", "i", $id],
        );
    } else if ($select === "adult") {
        $wheres = array(
            "family_id" => ["=", "i", $family_id],
            "done" => ["=", "i", false],
        );
    }

    $result = select($db, $col, "debt", wheres: $wheres);
    // print_r($result[0]);
    print_r($result[0]["id"]);

// $done = $result[0]["done"];
    // $done -= 1;
    $update_data = array(
        // "done" => ["i", $done],
        "done" => ["i", $result[0]["done"]-1],
    );

    $update_where = array(
        "id" => ["=", "i", $result[0]["id"]],
    );

    update($db, $update_data, "debt", wheres: $update_where);
    // // $sql = "UPDATE debt  SET done=77 WHERE amount=? AND  date=? AND reason=? limit 1";
    // $sql = "UPDATE debt SET done=77 WHERE id=?";
    // $stmt = $db->prepare($sql);
    // // $stmt = $db->bind_param("iss", $result[0]['amount'], $result[0]['date'], $result[0]['reason']);
    // $stmt = $db->bind_param("i", $result[0]["id"]);
    // $stmt->execute();

    $headers = [
        "Authorization: Bearer " . $channelToken,
        "Content-Type: application/json",
    ];


    $line_col = array(
        "UID",
    );

    $line_wheres = array(
        "id" => ["=", "i", $user["parent"]],
    );

    $line_result = select($line, $line_col, "LINEdatabase", wheres: $line_wheres, limits: 1);

    $messageData = [
        'type' => 'template',
        'altText' => 'ボタン',
        'template' => [
            'type' => 'buttons',
            'title' => $result[0]['amount'] . '円の返済申請が来ています。',
            'text' => '利用目的:' . $result[0]['purpose'],

            'actions' => [
                [
                    'type' => 'postback',
                    'label' => '返済を承認する',
                    'data' => 'repayment,' . $result[0]["id"],
                ],

            ]
        ]
    ];


    $post = [
        "to" => $line_result[0]["UID"],
        "messages" => [$messageData],
    ];

    $post = json_encode($post);

    $ch = curl_init("https://api.line.me/v2/bot/message/push");
    $options = [
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HEADER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => $post,
    ];
    curl_setopt_array($ch, $options);

    $result = curl_exec($ch);

    $errno = curl_errno($ch);
    if ($errno) {
        return;
    }

    unset($_POST["lending"]);

    //LINE処理終了
    /////////////////////

}
// TODO:利子と分割返済金額の表示
?>
<main>
    <div id="input_data" class="p-section p-section__bank">
        <?php
        $week_list = ["日", "月", "火", "水", "木", "金", "土"];
        $col = array(
            "id",
            "amount",
            "date",
            "repayment",
            "purpose",
            "done",
            "division",
            "reason",
            "family_id",
            "child_id",
        );

        if ($select === "child") {
            $wheres = array(
                "child_id" => ["=", "i", $user["id"]],
                "done" => ["<>", "i", 0],
            );
        } else if ($select === "adult") {
            $wheres = array(
                "family_id" => ["=", "i", $family_id],
                // "done" => ["=", "i", false],
                "done" => ["<>", "i", 0],
            );
        }

        $result = select($db, $col, "debt", wheres: $wheres);

        $family_col = [
            "interest",
        ];
        $family_where = [
            "id" => ["=", "i", $family_id],
        ];

        $family = select($db, $family_col, "family", wheres: $family_where);
        $interest = $family[0]["interest"];
        ?>

        <div id="" class="p-section p-section__bank-list">
            <!-- <div class="list_header" id="">
                <p>
                    日付
                    <span>(曜日)</span>
                </p>/
                <span>借りた金額</span>/
                <span>内容</span>
            </div> -->

            <div class="lending repayment">
                <h3 class="len_list_ty">へんさい</h3>
                <?php
                if (count($result) > 0) :
                    while ($row = current($result)) :
                ?>
                        <div class="list hide" id="debtDate<?php echo $row["id"] . '-' . h($row['date']); ?>" onclick="onClickDebtBanner('<?php echo $row['date'] . '\', \'' . $row['id']; ?>');">
                            <p>
                                <?php echo date("n月j日", strtotime($row["date"])); ?>
                                <span>(<?php echo ($week_list[date("w", strtotime($row["date"]))]); ?>)</span>
                            </p>
                            <span><?php echo $row["purpose"]; ?></span>
                        </div>
                        <div class="list list-box hidden" id="debtItem<?php echo $row["id"] . '-' . $row["date"]; ?>">
                            <div class="list-box__overview item<?php echo h($row["id"]); ?>">
                                <div class="repayment-date">
                                    <?php if ($row["division"] > 1) : ?>
                                        <p class="title">全体のお金</p>
                                        <table class="content">
                                            <tr>
                                                <td class="amount" colspan="2">
                                                    <?php echo h($row["amount"] * $row["done"] / $row["division"]); ?>円
                                                </td>
                                            </tr>
                                        </table>
                                    <?php endif; ?>
                                    <p class="title">返済予定日</p>
                                    <table class="content" onclick="">
                                        <?php
                                        $repayment = new DateTime($row["repayment"]);
                                        for ($i = 1; $i <= $row["division"]; $i++) { ?>
                                            <!-- <form method="post"> -->
                                            <?php if ($i + $row["done"] > $row["division"]) : ?>
                                            <tr>
                                                <td><?php echo $repayment->format("n月d日"); ?>
                                                    <!-- <i class="fa-regular fa-message" onclick=""></i> -->
                                                </td>
                                                <td class="amount">
                                                    <?php echo h($row["amount"] / $row["division"]); ?>円
                                                </td>
                                            </tr>
                                            <?php endif ?>
                                            <!-- </form> -->
                                        <?php
                                            $repayment = $repayment->modify("+1 month");
                                        }
                                        ?>
                                    </table>
                                    <table class="content" onclick="">
                                        <p class="title">かかるりし</p>
                                        <tr>
                                            <td class="amount">
                                                <?php echo $row["amount"] - ($row["amount"] / $interest); ?>円
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="reason" <?php echo $result[0]["reason"] == "" ? "hidden" : ""; ?>>
                                    <p class="title">理由</p>
                                    <p class="content">
                                        <?php
                                        // echo ($row["reason"] === "") ? "null" : "not null" . "<br>";
                                        if (isset($row["reason"]) && $row["reason"] !== "") {
                                            echo h($row["reason"]);
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="u-flex-box p-sp-data-box__button">
                                    <form action="./lending-edit.php" method="post">
                                        <input type="hidden" name="record_id" value="<?php echo h($row["id"]); ?>">
                                        <input type="submit" class="c-button c-button--bg-green edit" id="" value="更 新">
                                    </form>
                                    <form action="" method="post">
                                        <input type="hidden" name="lending" value="<?php echo h($row["id"]); ?>">
                                        <input type="submit" class="c-button c-button--bg-red repayment-btn" id="" value="返 済">
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php
                        next($result);
                    endwhile;
                else :
                    ?>
                    <p class="datanai">でーたがないよ！</p>
                    
                <?php endif; ?>
            </div>
            <style>
                .datanai {
                    font-size: 17px;
                    color: #ff2323;
                    font-weight: bold;
                    margin-top: 10px;
                    /* margin-left: 7rem;
                    margin-right: 7rem; */
                    padding-bottom: 10px;
                }
                .len_list_ty {
                    padding-top: 10px;
                }
            </style>

            <!-- <div class="lending request">
                <h3>しんせい</h3>
                <?php

                if ($select === "child") {
                    $wheres = array(
                        "child_id" => ["=", "i", $user["id"]],
                        "done" => ["=", "i", true],
                    );
                } else if ($select === "adult") {
                    $wheres = array(
                        "family_id" => ["=", "i", $family_id],
                        "done" => ["=", "i", true],
                    );
                }

                $result = select($db, $col, "debt", wheres: $wheres);

                if (count($result) > 0) :
                    while ($row = current($result)) :
                ?>
                        <div class="list done" id="debtDate<?php echo $row["id"] . '-' . h($row['date']); ?>" onclick="onClickDebtBanner('<?php echo $row['date'] . '\', \'' . $row['id']; ?>');">
                            <p>
                                <?php echo date("n月j日", strtotime($row["date"])); ?>
                                <span>(<?php echo ($week_list[date("w", strtotime($row["date"]))]); ?>)</span>
                            </p>
                            <span><?php echo $row["purpose"]; ?></span>
                        </div>
                        <div class="list list-box done hide" id="debtItem<?php echo $row["id"] . '-' . $row["date"]; ?>">
                            <div class="list-box__overview item<?php echo h($row["id"]); ?>">
                                <div class="u-flex-box p-sp-data-box__overview">
                                    <p>目的: <?php echo h($row["purpose"]); ?>
                                        <span>
                                            <?php
                                            echo "(返済予定日: " . h($row["repayment"]) . ")";
                                            ?>
                                            <i class="fa-regular fa-message" onclick=""></i>
                                        </span>
                                    </p>
                                    <p>
                                        <?php echo h($row["amount"]); ?>円
                                    </p>
                                </div>
                                <div class="p-sp-data-box__detail">
                                    <p>
                                        <?php
                                        //支払い方法の出力
                                        // echo ($row["reason"] === "") ? "null" : "not null" . "<br>";
                                        if (isset($row["reason"]) && $row["reason"] !== "") {
                                            echo "理由：" . h($row["reason"]);
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="u-flex-box p-sp-data-box__button">
                                    <form action="./record-edit.php" method="post">
                                        <input type="hidden" name="record_id" value="<?php echo h($id); ?>">
                                        <input type="submit" class="c-button c-button--bg-green edit" id="" value="更 新">
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php
                        next($result);
                    endwhile;
                else :
                    ?>
                    <p>No Data</p>
                    <hr>
                <?php
                endif;
                ?>
            </div> -->

            <div class="lending">
                <h3 class="len_list_ty">申請待ち</h3>
                <?php

                if ($select === "child") {
                    $wheres = array(
                        "child_id" => ["=", "i", $user["id"]],
                        "done" => ["=", "i", false],
                    );
                } else if ($select === "adult") {
                    $wheres = array(
                        "family_id" => ["=", "i", $family_id],
                        "done" => ["=", "i", false],
                    );
                }

                $result = select($db, $col, "debt", wheres: $wheres);

                if (count($result) > 0) :
                    while ($row = current($result)) :
                ?>
                        <div class="list wait" id="debtDate<?php echo $row["id"] . '-' . h($row['date']); ?>" onclick="onClickDebtBanner('<?php echo $row['date'] . '\', \'' . $row['id']; ?>');">
                            <p>
                                <?php echo date("n月j日", strtotime($row["date"])); ?>
                                <span>(<?php echo ($week_list[date("w", strtotime($row["date"]))]); ?>)</span>
                            </p>
                            <span><?php echo $row["purpose"]; ?></span>
                        </div>
                        <div class="list list-box wait hidden" id="debtItem<?php echo $row["id"] . '-' . $row["date"]; ?>">
                            <div class="list-box__overview item<?php echo h($row["id"]); ?>">
                                <div class="purpose">
                                    <p>目的</p>
                                    <p class="content"><?php echo h($row["purpose"]); ?></p>
                                </div>
                                <div class="repayment-date">
                                    <p>(返済予定日)</p>
                                    <?php
                                    if ($row["division"] > 1) {
                                        echo "全体のお金 :" . $row["amount"] . "円";
                                    }
                                    for ($i = 0; $i < $row["division"]; $i++) { ?>
                                        <form method="post">
                                            <span class="content" onclick="">
                                                <p><?php echo h(date("n月j日", strtotime($row["repayment"]))); ?>
                                                    <!-- <i class="fa-regular fa-message" onclick=""></i> -->
                                                </p>
                                                <p>
                                                    <?php echo h($row["amount"] / $row["division"]) * $interest; ?>円
                                                </p>
                                            </span>
                                        </form>
                                    <?php } ?>
                                    <span class="content interest" onclick="">
                                        <p>かかるりし</p>
                                        <p>
                                            <?php echo ($row["amount"] * $interest) - $row["amount"]; ?>円
                                        </p>
                                    </span>
                                </div>
                                <div class="reason" <?php echo $result[0]["reason"] == "" ? "hidden" : ""; ?>>
                                    <p>理由</p>
                                    <p class="content">
                                        <?php
                                        // echo ($row["reason"] === "") ? "null" : "not null" . "<br>";
                                        if (isset($row["reason"]) && $row["reason"] !== "") {
                                            echo h($row["reason"]);
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="u-flex-box p-sp-data-box__button">
                                    <form action="../record-edit.php" method="post">
                                        <input type="hidden" name="record_id" value="<?php echo h($id); ?>">
                                        <input type="submit" class="c-button c-button--bg-green edit" id="" value="更 新">
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php
                        next($result);
                    endwhile;
                else :
                    ?>
                    <p class="datanai">でーたがないよ！</p>
                <?php
                endif;
                ?>
            </div>
        </div>
        <!-- <a class="back" href="../index.php"><i class="fa-solid fa-left-long"></i> ホームへ</a> -->
        <section class="p-section p-section__back-home">
            <a class="c-button c-button--bg-gray" href="../index.php">ホームに戻る</a>
        </section>
    </div>
</main>

<?php
$footer_back = "off";
include_once("../component/common/footer.php");
?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="../js/import.js"></script>
<script src="../js/functions.js"></script>
<script src="../js/jquery.cookie.js"></script>

</body>

</html>