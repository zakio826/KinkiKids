<!-- ヘッダー -->
<?php
$page_title = "収支";
$stylesheet_name = "spending_input2.css";
include("../include/header.php");
require_once($absolute_path."lib/functions.php");

require_once($absolute_path."lib/db_entry.php"); //収支登録でデータベースに登録の際に使うもの(別の場所に移動可)
?>

<?php
if (isset($_GET["pick_date"])) {
    $date = strtotime($_GET["pick_date"]);
} else {
    $date = strtotime((new DateTime("now"))->format("Y-m-d"));
}
?>


<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main class="l-main">
    <!-- 収支データ入力 -->
    <section class="p-section p-section__records-input">
        <form class="p-form p-form--input-record" name="recordInput" action="" method="POST">
            <input type="hidden" name="input_time" id="input_time" value="<?php echo date("Y/m/d-H:i:s"); ?>">


            <!-- 基本データ -->
            <div class="p-form__flex-input">
                <p class="long-name">日付</p>
                <label for="date"><input type="date" name="date" id="date" value="<?php echo  date("Y-m-d", $date); ?>" required></label>
            </div>
            <div class="p-form__flex-input">
                <p class="long-name">タイトル</p>
                <input type="text" name="title" id="title" maxlength="15" required>
            </div>
            <div class="p-form__flex-input">
                <p class="long-name">金額</p>
                <input type="number" name="amount" id="amount" step="1" maxlength="5" required>
            </div>
            <div class="p-form__flex-input type">
                <input id="spending" type="radio" name="type" value="0" onchange="onRadioChangeType(0);" required>
                <label for="spending" class="spinradio">支出 </label>
                <input type="radio" name="type" id="income" value="1" onchange="onRadioChangeType(1);">
                <label for="income" class="spinradio">収入 </label>
            </div>
       
            <!-- セレクトボックス(支出カテゴリ) -->
            <div class="u-js__show-switch flex p-form__flex-input sp-change-order" id="spendingCategoryBox">
                <p class="long-name">支出カテゴリー</p>
                <select name="spending_category" id="spendingCategory">
                    <option value="0">選択してください</option>
                    <?php
                    $user_id = $_SESSION['user_id'];
                    $stmt_spendingcat = $db->prepare('SELECT income_expense_category_id, income_expense_category_name FROM income_expense_category WHERE (user_id = 31 OR user_id = :user_id) AND income_expense_flag = 1');
                    $stmt_spendingcat->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    sql_check($stmt_spendingcat, $db);
                    $stmt_spendingcat->execute();
                        while ($row = $stmt_spendingcat->fetch(PDO::FETCH_ASSOC)) :
                    ?>
                    <option value="<?php echo h($row['income_expense_category_id']); ?>"><?php echo h($row['income_expense_category_name']); ?></option>
                    <?php endwhile; ?>
                </select>
                <a class="c-button c-button--bg-gray" href="./item-edit.php">編集</a>
            </div>


            <!-- セレクトボックス(支払方法) -->
            <div id="paymentMethodBox" class="u-js__show-switch flex p-form__flex-input sp-change-order">
                <p class="long-name">支払い方法</p>
                <select name="payment_method" id="paymentMethod">
                    <option value="0">選択してください</option>
                    <?php
                    $user_id = $_SESSION['user_id'];
                    $stmt_spendingcat = $db->prepare('SELECT payment_id,payment_name FROM payment WHERE (user_id = 31 OR user_id = :user_id)');
                    $stmt_spendingcat->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    sql_check($stmt_spendingcat, $db);
                    $stmt_spendingcat->execute();
                        while ($row = $stmt_spendingcat->fetch(PDO::FETCH_ASSOC)) :
                    ?>
                    <option value="<?php echo h($row['payment_id']); ?>"><?php echo h($row['payment_name']); ?></option>
                    <?php endwhile; ?>
                </select>
                <a class="c-button c-button--bg-gray" href="./item-edit.php">編集</a>
            </div>
       
            <!-- セレクトボックス(収入カテゴリ) -->
            <div class="u-js__show-switch flex p-form__flex-input sp-change-order" id="incomeCategoryBox">
                <p class="long-name">収入カテゴリー</p>
                <select name="income_category" id="incomeCategory">
                    <option value="0">選択してください</option>
                    <?php
                    $user_id = $_SESSION['user_id'];
                    $stmt_spendingcat = $db->prepare('SELECT income_expense_category_id, income_expense_category_name FROM income_expense_category WHERE (user_id = 31 OR user_id = :user_id) AND income_expense_flag = 0');
                    $stmt_spendingcat->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    sql_check($stmt_spendingcat, $db);
                    $stmt_spendingcat->execute();
                        while ($row = $stmt_spendingcat->fetch(PDO::FETCH_ASSOC)) :
                    ?>
                    <option value="<?php echo h($row['income_expense_category_id']); ?>"><?php echo h($row['income_expense_category_name']); ?></option>
                    <?php endwhile; ?>
                </select>
                <a class="c-button c-button--bg-gray" href="./item-edit.php">編集</a>
            </div>


            <!-- 入力したデータの詳細情報 -->
            <div>
                <textarea name="memo" id="" cols="30" rows="5" class="spending_input_textarea" placeholder="入力収支の詳細"></textarea>
            </div>


            <input class="button btn-touroku" type="submit" value="登録">
        </form>
    </section>
</main>

<!-- ナビゲーションバー -->
<?php include_once("../include/bottom_nav.php") ?>


<!-- JavaScript -->
<script src="<?php echo $absolute_path; ?>static/js/spending_input/radio.js"></script>
<script src="<?php echo $absolute_path; ?>static/js/spending_input/import.js"></script>
<script src="<?php echo $absolute_path; ?>static/js/spending_input/functions.js"></script>

<!-- フッター -->
<?php include_once("../include/footer.php"); ?>