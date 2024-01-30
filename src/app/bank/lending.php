<?php
require_once("../component/common/dbconnect.php");
include_once("../component/common/functions.php");
include_once("../component/common/session.php");
// -- スマホ画面のメニューバーのhtml 
include_once("../component/index/sp-tab2.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $amount = filter_input(INPUT_POST, "amount", FILTER_SANITIZE_NUMBER_INT);
    $today = filter_input(INPUT_POST, "today", FILTER_SANITIZE_SPECIAL_CHARS);
    $purpose = filter_input(INPUT_POST, "purpose", FILTER_SANITIZE_SPECIAL_CHARS);
    $reason = filter_input(INPUT_POST, "reason", FILTER_SANITIZE_SPECIAL_CHARS);
    $repayment = filter_input(INPUT_POST, "repayment", FILTER_SANITIZE_SPECIAL_CHARS);
    $division = filter_input(INPUT_POST, "division", FILTER_SANITIZE_SPECIAL_CHARS);
    $done = $division;

    $_SESSION["lending"] = array(
        "amount" => $amount,
        "today" => $today,
        "purpose" => $purpose,
        "reason" => $reason,
        "repayment" => $repayment,
        "division" => $division,
    );

    $sql = "INSERT INTO debt(amount, date, repayment, purpose, reason, done, family_id, child_id, division) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("issssiiii", $amount, $today, $repayment, $purpose, $reason, $done, $family_id, $user["id"], $division);
    sql_check($stmt, $db);

    $debt_col = [
        "id"
    ];

    $debt_order = [
        "order" => ["id", true],
    ];

    $debt = select($db, $debt_col, "debt", group_order: $debt_order);

    //////////////////////////////////
    //Line機能 only 森岡
    $sql = "INSERT INTO Bank (id,money,flag,date, debt_id) VALUES (?, ?, 1, ?, ?)";
    $stmt = $line->prepare($sql);
    $stmt->bind_param("iisi", $user["id"], $amount, $repayment, $debt[0]["id"]);
    $stmt->execute();

    $sql = "SELECT number FROM Bank WHERE debt_id = ?";
    $stmt = $line->prepare($sql);
    $stmt->bind_param("i", $debt[0]["id"]);
    $stmt->execute();
    $stmt->bind_result($number);
    $stmt->fetch();
    $stmt->close();
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
            'title' => $amount . '円の貸出申請が来ています。',
            'text' => '理由:' . $reason,

            'actions' => [
                [
                    'type' => 'postback',
                    'label' => '承諾',
                    'data' => 'bankok,' . $number,
                ],

                [
                    'type' => 'postback',
                    'label' => '拒否',
                    'data' => 'bankno,' . $number,
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

    ///////////////////////////////////
} else {
    $_SESSION["lending"] = array(
        "amount",
        "today",
        "purpose",
        "reason",
        "repayment",
    );

    $date = new DateTime();
    $today = $date->format("Y-m-d");
    $today_display = $date->format("Y年m月d日");
    $repayment = $date->modify("+1 month")->format("Y-m-d");

    $col = [
        "interest"
    ];
    $where = [
        "id" => ["=", "i", $family_id],
    ];

    $select = select($db, $col, "family", wheres: $where);
    $interest = $select[0]["interest"];
}

$page_title = "貸出申請";
include_once("./header.php");
?>
<!--
// TODO:利子と分割返済金額の表示
-->
<section class="p-section__bank p-section__lending">
    <div id="input_data" class="lending_form">
        <form method="POST">
            <div class="p-form p-form--input-record">
                <div class="p-form__flex-input">
                    <p>日付</p>
                    <input type="hidden" name="today" id="today" value="<?php echo isset($today) ? $today : ""; ?>">
                    <p>
                        <?php echo $today_display; ?>
                    </p>
                </div>

                <div class="p-form__flex-input">
                    <p>目的</p>
                    <input type="text" name="purpose" id="purpose" value="<?php echo isset($purpose) ? $purpose : ""; ?>" required>
                </div>

                <div class="p-form__flex-input">
                    <p class="long-name">借りたい金額</p>
                    <input type="number" id="amount" pattern="^[0-9]+$" value="<?php echo isset($amount) ? $amount : ""; ?>" required>
                    円
                </div>

                <div class="p-form__flex-input">
                    <p>何回に分ける？</p>
                    <select name="division" id="division" <?php echo $isPC ? "onchange='repayment_money();'" : "onclick='repayment_money();'"; ?>>
                        <?php $max_division = 5; ?>
                        <?php for ($i = 1; $i <= $max_division; $i++) : ?>
                            <option value="<?php echo $i; ?>" <?php if ($i == 1) echo "selected"; ?>>
                                <?php echo $i; ?>回
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="p-form__flex-input repayment_amount">
                    <p>１回で返すお金</p>
                    <p>
                        <span class="repayment_money amount">
                            0
                        </span>円
                        <input type="hidden" name="repayment_once" id="repayment_once">
                    </p>
                    <input type="hidden" name="repayment" id="repayment" value="<?php echo $repayment; ?>">
                </div>

                <div class="p-form__flex-input repayment_amount">
                    <input type="hidden" name="interest" id="interest" value="<?php echo isset($interest) ? $interest : ""; ?>">
                    <input type="hidden" name="amount" id="total_amount">
                    <p>全体のお金(＋利子)</p>
                    <p>
                        <span class="total_amount amount">
                            0
                        </span>円
                    </p>
                </div>
                <div class="p-form__flex-input">
                    <textarea name="reason" id="" cols="45" rows="5" placeholder="理由"></textarea>
                </div>
            </div>
            <input type="submit" name="lending" class="c-button c-button--bg-blue lending" value="申請">
        </form>

        <a href="../index.php">キャンセル</a>
    </div>
</section>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="../js/jquery.cookie.js"></script>

<script src="../js/import.js"></script>
<script src="../js/functions.js"></script>
<?php
$footer_back = true;
include("../component/common/footer.php");
?>