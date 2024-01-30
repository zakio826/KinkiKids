<?php
require_once("../component/common/dbconnect.php");
include_once("../component/common/functions.php");
include_once("../component/common/session.php");
// -- スマホ画面のメニューバーのhtml 
include_once("../component/index/sp-tab2.php");

if ($_POST["record_id"] && $_POST["record_id"] > 0) :
  $record_id = $_POST["record_id"];
  echo $record_id;
// echo $select;
else :
  header("Location: ../index.php?dataOperation=error");
  exit();
endif;

// $sql = "SELECT count(*), id, date, title, amount, spending_category, income_category, type, payment_method, creditcard, qr, memo FROM records WHERE id = ? AND child_id = ? LIMIT 1";
// $stmt = $db->prepare($sql);
// $stmt->bind_param("ii", $record_id, $user["id"]);
// sql_check($stmt, $db);

// $stmt->bind_result($count, $id, $date, $title, $amount, $spending_category, $income_category, $type, $payment_method, $credit, $qr, $memo);
// $stmt->fetch();

$sql = "SELECT count(*) FROM debt WHERE id = ? AND child_id = ? LIMIT 1";
$stmt = $db->prepare($sql);
$stmt->bind_param("ii", $record_id, $user["id"]);
sql_check($stmt, $db);

$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

$col = array(
  "id",
  "amount",
  "date",
  "repayment",
  "purpose",
  "reason",
  "done",
  "family_id",
  "child_id",
);

$wheres = array(
  "id" => ["=", "i", $record_id],
  "child_id" => ["=", "i", $user["id"]],
);

$result = select($db, $col, "debt", wheres: $wheres);
$br = "<br>";
echo $br . $result[0]["date"] . $br . $result[0]["purpose"] . $br . $result[0]["amount"] . $br . $result[0]["repayment"];

if ($count !== 1) :
  header(("Location: ../index.php?dataOperation=error"));
endif;

// $br = "<br>";
// echo $br . $date . $br . $title . $br . $amount . $br . $payment_method;


$page_title = "レコード編集";
include_once("../component/common/header.php");
?>

<main class="l-main">
  <!-- 収支データ編集 -->
  <section class="p-section p-section__records-input">
    <h2 class="c-text c-text__subtitle">【レコード編集】</h2>

    <form class="p-form p-form--input-record" name="recordInput" action="./lending-update.php" method="POST">
      <input type="hidden" name="record_id" value="<?php echo h($result[0]["id"]); ?>">
      <div class="p-form__flex-input">
        <p>日付</p>
        <div class="p-form--input-record__dateinput u-flex-box">
          <span onclick="onChangeInputDate('past');">＜</span>
          <input type="date" name="date" id="date" value="<?php echo h($result[0]["date"]); ?>" required>
          <span onclick="onChangeInputDate('future');">＞</span>
        </div>
      </div>

      <div class="p-form__flex-input">
        <p>日付</p>
        <div class="p-form--input-record__dateinput u-flex-box">
          <span onclick="onChangeInputDate('past');">＜</span>
          <input type="date" name="repayment" id="repayment" value="<?php echo h($result[0]["repayment"]); ?>" required>
          <span onclick="onChangeInputDate('future');">＞</span>
        </div>
      </div>

      <div class="p-form__flex-input">
        <p>タイトル</p>
        <input type="text" name="purpose" id="purpose" value="<?php echo h($result[0]["purpose"]); ?>" maxlength="15" required>
      </div>

      <div class="p-form__flex-input">
        <p>金額</p>
        <input type="number" name="amount" id="amount" value="<?php echo h($result[0]["amount"]); ?>" step="1" maxlength="7" required>
      </div>

      <div>
        <textarea name="reason" id="" cols="45" rows="5" placeholder="入力収支の詳細"><?php echo h($result[0]["reason"]) ?></textarea>
      </div>

      <input class="c-button c-button--bg-blue" type="submit" name="record_update" value="更新">
    </form>
  </section>
  <!-- 収支データ編集 -->

  <section class="p-section p-section__back-home">
    <a href="./index.php" class="c-button c-button--bg-gray">ホームに戻る</a>
  </section>
</main>

<?php
//ディレクトリ直下の場合
$footer_back = "on"; //login.php以外に記述
include_once("../component/common/footer.php");
?>

<script src="../js/radio.js"></script>
<script src="../js/import.js"></script>
<script src="../js/functions.js"></script>

<script src="../js/record-edit.js"></script>
</body>

</html>