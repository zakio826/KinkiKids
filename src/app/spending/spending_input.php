<?php
	$page_title = "収支";
	require_once("../include/header.php");
	require_once($absolute_path."lib/functions.php");
?>

<!DOCTYPE html>
<html lang="ja">

<!-- ヘッダー -->
<?php
$page_title = "カレンダー";
$stylesheet_name = "spending_input.css";
include("../include/header.php");
?>


<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main class="l-main">

	<!-- 収支データ入力 -->
	<section class="p-section p-section__records-input">

		<form class="p-form p-form--input-record" name="recordInput" action="" method="POST">
			<input type="hidden" name="input_time" id="input_time" value="<?php echo date("Y/m/d-H:i:s"); ?>">
			<div class="p-form__flex-input">
				<p>日付</p>
				<label for="date"><input type="date" name="date" id="date" value="<?php echo date("Y-m-d"); ?>" required></label>
			</div>

			<div class="p-form__flex-input">
				<p>タイトル</p>
				<input type="text" name="title" id="title" maxlength="15" required>
			</div>

        <div class="u-js__show-switch flex p-form__flex-input sp-change-order" id="spendingCategoryBox">
        <p class="long-name">支出カテゴリー</p>
        <select name="spending_category" id="spendingCategory">
            <option value="0">選択してください</option>
            <?php
              $stmt_spendingcat = $db->prepare('SELECT income_expense_category_id,income_expense_category_name FROM income_expense_category');
              sql_check($stmt_spendingcat, $db);
              $stmt_spendingcat->bind_result($id, $name);
                while ($stmt_spendingcat->fetch()) :
            ?>
            <option value="<?php echo h($id); ?>"><?php echo h($name); ?></option>
            <?php endwhile; ?>
        </select>
        <!-- <a class="c-button c-button--bg-gray" href="./item-edit.php">編集</a> -->
        </div>

        <div class="u-js__show-switch flex p-form__flex-input sp-change-order" id="incomeCategoryBox">
        <p class="long-name">収入カテゴリー</p>
        <select name="income_category" id="incomeCategory">
            <?php
              $stmt_incomecat = $db->prepare('SELECT income_expense_category_id,income_expense_category_name FROM income_expense_category');
              sql_check($stmt_incomecat, $db);
              $stmt_incomecat->bind_result($id, $name);
              while ($stmt_incomecat->fetch()) :
              ?>
                <option value="<?php echo h($id); ?>"><?php echo h($name); ?></option>
            <?php endwhile; ?>
        </select>
        <!-- <a class="c-button c-button--bg-gray" href="./item-edit.php">編集</a> -->
        </div>

			<div class="u-js__show-switch flex p-form__flex-input sp-change-order" id="spendingCategoryBox">
				<p class="long-name">支出カテゴリー</p>
				<select name="spending_category" id="spendingCategory">
					<option value="0">選択してください</option>
					<option value="1">食費</option>
					<option value="2">日用品</option>
					<option value="3">交通費</option>
				</select>
				<!-- <a class="c-button c-button--bg-gray" href="./item-edit.php">編集</a> -->
			</div>

			<div class="u-js__show-switch flex p-form__flex-input sp-change-order" id="incomeCategoryBox">
				<p class="long-name">収入カテゴリー</p>
				<select name="income_category" id="incomeCategory">
					<option value="0">選択してください</option>
					<option value="1">給料</option>
					<option value="2">お小遣い</option>
				</select>
				<!-- <a class="c-button c-button--bg-gray" href="./item-edit.php">編集</a> -->
			</div>

			<div id="paymentMethodBox" class="u-js__show-switch flex p-form__flex-input sp-change-order">
				<p class="long-name">支払い方法</p>
				<select name="payment_method" id="paymentMethod" onchange="hasChildSelect('2', creditSelectBox, qrChecked);hasChildSelect('3', qrSelectBox, creditChecked);">
					<option value="0">選択してください</option>
					<option value="1">現金</option>
					<option value="2" id="radioCredit">クレジット</option>
					<option value="3" id="radioQr">スマホ決済</option>
				</select>
				<!-- <a class="c-button c-button--bg-gray" href="./item-edit.php">編集</a> -->
			</div>

			<div>
				<textarea name="memo" id="" cols="45" rows="5" placeholder="入力収支の詳細"></textarea>
			</div>

			<input class="c-button c-button--bg-blue" type="submit" value="登録">
		</form>
	</section>
</main>


<!-- JavaScript -->
<script src="<?php echo $absolute_path; ?>static/js/spending_input/radio.js"></script>
<script src="<?php echo $absolute_path; ?>static/js/spending_input/import.js"></script>
<script src="<?php echo $absolute_path; ?>static/js/spending_input/functions.js"></script>

<!-- フッター -->
<?php include_once("../include/footer.php"); ?>