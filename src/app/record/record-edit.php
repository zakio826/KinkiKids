<?php
require_once("./component/common/dbconnect.php");
include_once("./component/common/functions.php");
include_once("./component/common/session.php");

if ($_POST["record_id"] && $_POST["record_id"] > 0) :
  $record_id = $_POST["record_id"];
else :
  header("Location: ./index.php?dataOperation=error");
  exit();
endif;

if ($select == "adult") :
  $sql = "SELECT count(*), id, date, title, amount, spending_category, income_category, type, payment_method, creditcard, qr, memo FROM records WHERE id = ? AND family_id = ? LIMIT 1";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("ii", $record_id, $family_id);
elseif ($select == "child") :
  $sql = "SELECT count(*), id, date, title, amount, spending_category, income_category, type, payment_method, creditcard, qr, memo FROM records WHERE id = ? AND child_id = ? LIMIT 1";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("ii", $record_id, $user["id"]);
endif;
sql_check($stmt, $db);

$stmt->bind_result($count, $id, $date, $title, $amount, $spending_category, $income_category, $type, $payment_method, $credit, $qr, $memo);
$stmt->fetch();

if ($count !== 1) :
  header(("Location: ./index.php?dataOperation=error"));
endif;

$stmt->close();

$page_title = "レコード編集";
include_once("./component/common/header.php");
?>

<main class="l-main">
  <!-- 収支データ編集 -->
  <section class="p-section p-section__records-input">
    <h2 class="c-text c-text__subtitle">【レコード編集】</h2>

    <form class="p-form p-form--input-record" name="recordInput" action="./record-create.php" method="POST">
      <input type="hidden" id="select" value="<?php echo $select; ?>">
      <input type="hidden" name="record_id" value="<?php echo h($id); ?>">
      <input type="hidden" name="input_time" id="input_time" value="<?php echo date("Y/m/d-H:i:s"); ?>">
      <div class="p-form__flex-input">
        <p>日付</p>
        <div class="p-form--input-record__dateinput u-flex-box">
          <span onclick="onChangeInputDate('past');">＜</span>
          <input type="date" name="date" id="date" value="<?php echo h($date); ?>" required>
          <span onclick="onChangeInputDate('future');">＞</span>
        </div>
      </div>

      <div class="p-form__flex-input">
        <p>タイトル</p>
        <input type="text" name="title" id="title" value="<?php echo h($title); ?>" maxlength="15" required>
      </div>

      <div class="p-form__flex-input">
        <p>金額</p>
        <input type="number" name="amount" id="amount" value="<?php echo h($amount); ?>" step="1" maxlength="7" required>
      </div>

      <div class="p-form__flex-input type">
        <input type="radio" name="type" id="spending" value="0" <?php echo $type === 0 ? "checked" : ""; ?> onchange="onRadioChangeType(0);" required>
        <label for="spending">支出</label>
        <input type="radio" name="type" id="income" value="1" <?php echo $type === 1 ? "checked" : ""; ?> onchange="onRadioChangeType(1);">
        <label for="income">収入</label>
      </div>

      <div class="u-js__show-switch flex p-form__flex-input sp-change-order" id="spendingCategoryBox">
        <p class="long-name">支出カテゴリー</p>
        <select name="spending_category" id="spendingCategory">
          <option value="0">選択してください</option>

          <?php
          if ($select == "adult") :
            $stmt_spendingcat = $db->prepare("SELECT * FROM spending_category WHERE family_id = ? GROUP BY name");
            $stmt_spendingcat->bind_param("i", $family_id);
          endif;
          if ($select == "child") :
            $stmt_spendingcat = $db->prepare("SELECT * FROM spending_category WHERE child_id = ?");
            $stmt_spendingcat->bind_param("i", $user["id"]);
          endif;
          sql_check($stmt_spendingcat, $db);
          $stmt_spendingcat->bind_result($id, $name, $user, $child, $family);
          while ($stmt_spendingcat->fetch()) :
          ?>
            <option value="<?php echo h($id); ?>" <?php echo $spending_category === $id ? "selected" : ""; ?>><?php echo h($name); ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="u-js__show-switch flex p-form__flex-input sp-change-order" id="incomeCategoryBox">
        <p class="long-name">収入カテゴリー</p>
        <select name="income_category" id="incomeCategory">
          <option value="0">選択してください</option>
          <?php
          if ($select == "adult") :
            $stmt_incomecat = $db->prepare("SELECT * FROM income_category WHERE family_id = ? GROUP BY name");
            $stmt_incomecat->bind_param("i", $family_id);
          endif;
          if ($select == "child") :
            $stmt_incomecat = $db->prepare("SELECT * FROM income_category WHERE child_id = ?");
            $stmt_incomecat->bind_param("i", $user["id"]);
          endif;
          sql_check($stmt_incomecat, $db);
          $stmt_incomecat->bind_result($id, $name, $user, $child, $family);
          while ($stmt_incomecat->fetch()) :
          ?>
            <option value="<?php echo h($id); ?>" <?php echo $income_category === $id ? "selected" : ""; ?>><?php echo h($name); ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <?php if ($select === "adult") : ?>
        <div id="paymentMethodBox" class="u-js__show-switch flex p-form__flex-input sp-change-order">
          <p class="long-name">支払い方法</p>
          <select name="payment_method" id="paymentMethod" onchange="hasChildSelect('2', creditSelectBox, qrChecked);hasChildSelect('3', qrSelectBox, creditChecked);">
            <option value="0">選択してください</option>

            <?php
            $fixedPaymentMethod = ["現金", "クレジット", "スマホ決済"];
            $fixedPaymentMethod_id = ["", "radioCredit", "radioQr"];
            for ($i = 0; $i < 3; $i++) :
            ?>
              <option value="<?php echo $i + 1 ?>" id="<?php echo $fixedPaymentMethod_id[$i] ?>" <?php echo $payment_method === $i + 1 ? "selected" : ""; ?>><?php echo $fixedPaymentMethod[$i] ?></option>
            <?php endfor; ?>

            <?php
            if ($select == "adult") :
              $stmt_paymethod = $db->prepare("SELECT * FROM payment_method WHERE id > 3 AND family_id = ?");
              $stmt_paymethod->bind_param("i", $family_id);
            endif;
            if ($select == "child") :
              $stmt_paymethod = $db->prepare("SELECT * FROM payment_method WHERE id > 3 AND child_id = ?");
              $stmt_paymethod->bind_param("i", $user["id"]);
            endif;
            sql_check($stmt_paymethod, $db);
            $stmt_paymethod->bind_result($id, $name, $user, $child, $family);
            while ($stmt_paymethod->fetch()) :
            ?>
              <option value="<?php echo h($id) ?>" <?php echo $payment_method === $id ? "selected" : ""; ?>><?php echo h($name); ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="u-js__show-switch flex p-form__flex-input sp-change-order" id="creditSelectBox">
          <p class="long-name">クレジットカード</p>
          <div class="p-form__item-box">
            <select name="credit">
              <option value="0">選択しない</option>
              <?php
              if ($select == "adult") :
                $stmt_credit = $db->prepare("SELECT * FROM creditcard WHERE family_id = ?");
                $stmt_credit->bind_param("i", $family_id);
                sql_check($stmt_credit, $db);
              endif;
              if ($select == "child") :
                $stmt_credit = $db->prepare("SELECT * FROM creditcard WHERE child_id = ?");
                $stmt_credit->bind_param("i", $user["id"]);
                sql_check($stmt_credit, $db);
              endif;
              $stmt_credit->bind_result($id, $name, $user, $child, $family);
              while ($stmt_credit->fetch()) :
              ?>
                <option value="<?php echo h($id) ?>" <?php echo $credit === $id ? "selected" : ""; ?>><?php echo h($name) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
        </div>

        <div class="u-js__show-switch flex p-form__flex-input sp-change-order" id="qrSelectBox">
          <p class="long-name">スマホ決済種類</p>
          <div class="p-form__item-box">
            <select name="qr">
              <option value="0">選択しない</option>
              <?php
              if ($select == "adult") :
                $stmt_qr = $db->prepare("SELECT * FROM qr WHERE family_id = ?");
                $stmt_qr->bind_param("i", $family_id);
              endif;
              if ($select == "child") :
                $stmt_qr = $db->prepare("SELECT * FROM qr WHERE child_id = ?");
                $stmt_qr->bind_param("i", $user["id"]);
              endif;
              sql_check($stmt_qr, $db);
              $stmt_qr->bind_result($id, $name, $user, $child, $family);
              while ($stmt_qr->fetch()) :
              ?>
                <option value="<?php echo h($id) ?>" <?php echo $qr === $id ? "selected" : ""; ?>><?php echo h($name) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
        </div>
      <?php endif; ?>

      <div>
        <textarea name="memo" id="" cols="45" rows="5" placeholder="入力収支の詳細"><?php echo h($memo) ?></textarea>
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
include_once("./component/common/footer.php");
?>

<script src="./js/radio.js"></script>
<script src="./js/import.js"></script>
<script src="./js/functions.js"></script>

<script src="./js/record-edit.js"></script>
</body>

</html>