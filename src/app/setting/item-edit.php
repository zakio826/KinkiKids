<?php
require_once("./component/common/dbconnect.php");
include_once("./component/common/functions.php");
include_once("./component/common/session.php");
// -- スマホ画面のメニューバーのhtml 
include_once("./component/index/sp-tab2.php");

if (isset($_GET["editItem"]) && $_GET["editItem"] >= 0 && $_GET["editItem"] <= 5) :
  $editItem = $_GET["editItem"];
else :
  header("location: ./index.php");
  exit();
endif;

if (isset($_GET["date"]) && isset($_GET["title"]) && isset($_GET["amount"]) && isset($_GET["type"]) && isset($_GET["spendingCat"]) && isset($_GET["paymentMethod"])) {
  $_SESSION["r_date"] = $_GET["date"];
  $_SESSION["r_title"] = $_GET["title"];
  $_SESSION["r_amount"] = $_GET["amount"];
  $_SESSION["r_type"] = $_GET["type"];
  $_SESSION["r_spendingCat"] = $_GET["spendingCat"];
  $_SESSION["r_paymentMethod"] = $_GET["paymentMethod"];
}

// echo $_SESSION["r_title"];

if ($editItem === "0") :
  $subTitle = "支出カテゴリー";
elseif ($editItem === "1") :
  $subTitle = "収入カテゴリー";
elseif ($editItem === "2") :
  $subTitle = "支払い方法";
elseif ($editItem === "3") :
  $subTitle = "クレジットカード";
elseif ($editItem === "4") :
  $subTitle = "スマホ決済";
elseif ($editItem === "5") :
  $subTitle = "お手伝い";
endif;
// echo $editItem;

$page_title = $subTitle . "編集";
include_once("./component/common/header.php");
?>

<main class="l-main">

  <!-- 操作完了コンテンツ -->
  <?php if (isset($_GET["dataOperation"]) && ($_GET["dataOperation"] === "delete" || $_GET["dataOperation"] === "update" || $_GET["dataOperation"] === "error" || $_GET["dataOperation"] === "duplicate")) : ?>
    <section class="p-section p-section__full-screen" id="doneOperateBox">
      <div class="p-message-box <?php echo ($_GET['dataOperation'] === 'error') ? 'line-red' : 'line-blue'; ?>">
        <p id="doneText">
          <?php
          if ($_GET["dataOperation"] === "error") :
            echo "正しく処理されませんでした";
          elseif ($_GET["dataOperation"] === "delete") :
            echo "削除しました";
          elseif ($_GET["dataOperation"] === "update") :
            echo "更新しました";
          elseif ($_GET["dataOperation"] === "duplicate") :
            echo "すでに登録済みです";
          endif;
          ?>
        </p>
        <button class="c-button <?php echo ($_GET['dataOperation'] === 'error') ? 'c-button--bg-darkred' : 'c-button--bg-blue'; ?>" onclick="onClickOkButton('?editItem=<?php echo $editItem; ?>');">OK</button>
      </div>
    </section>
  <?php endif; ?>
  <!-- //操作完了コンテンツ -->

  <?php if (isset($_SESSION["login_times"]) && $_SESSION["login_times"] === "first") : ?>
    <h2 class="c-text c-text__subtitle"><?php echo "【" . $subTitle . "編集】"; ?></h2>
    <section class="p-section p-section__message">
      <div class="p-message-box p-message-box--success">
        <p>
          <!-- <?php //echo h($nickname);
                ?>さん、 -->
          金記キッズへようこそ！<br>
          まずは支出カテゴリーを登録しましょう。<br>
          支出のカテゴリーが登録できたら、【他のカテゴリーを編集】から他の項目も登録してみましょう！<br>
          <span>※このメッセージは画面を更新・他ページへ遷移すると消えます</span>
        </p>
      </div>
    </section>

    <?php unset($_SESSION["login_times"]); ?>
  <?php endif; ?>

  <section class="p-section p-section__category-table">
    <div>
      <table class="p-table p-table--category">
        <?php
        if ($select === "adult") :
          $table_list = ["spending_category", "income_category", "payment_method", "creditcard", "qr", "help"];
        elseif ($select === "child") :
          $table_list = ["spending_category", "income_category", "payment_method", "creditcard", "qr"];
        endif;
        $table_name = $table_list[$editItem];
        ?>
        <tr class="p-table__head">
          <th>項目</th>
          <?php echo $table_name === "help" ? "<th>ポイント</th>" : ""; ?>
          <th>操作</th>
        </tr>
        <?php
        if (in_array($table_name, $table_list) !== false) :
          if ($select === "adult") {
            if ($table_name === "help") :
              $stmt = $db->prepare("SELECT id, title, point FROM {$table_name} WHERE family_id = ? OR family_id = 0");
            elseif ($table_name === "payment_method") :
              $stmt = $db->prepare("SELECT id, name FROM {$table_name} WHERE family_id = ? OR family_id = 0");
            else :
              $stmt = $db->prepare("SELECT id, name FROM {$table_name} WHERE family_id = ?");
            endif;
            $stmt->bind_param("i", $family_id);
          } elseif ($select === "child") {
            if ($table_name === "payment_method") :
              $stmt = $db->prepare("SELECT id, name FROM {$table_name} WHERE child_id = ? OR child_id = 0");
            else :
              $stmt = $db->prepare("SELECT id, name FROM {$table_name} WHERE child_id = ?");
            endif;
            $stmt->bind_param("i", $user["id"]);
          }

          sql_check($stmt, $db);
          $stmt->store_result();
          $count = $stmt->num_rows();
          if ($table_name === "help") :
            $stmt->bind_result($id, $name, $point);
          else :
            $stmt->bind_result($id, $name);
          endif;
          while ($stmt->fetch()) :
        ?>
            <tr class="p-table__item">
              <td><?php echo h($name); ?></td>
              <?php echo $table_name === "help" ? "<td class='point'>" . h($point) . "pt</td>" : ""; ?>
              <td>
                <?php if ($table_name === "payment_method" && $id <= 4) : ?>
                  操作不可
                <?php else : ?>
                  <button onclick="onClickUpdate('<?php echo h($id); ?>', '<?php echo h($name); ?>'<?php echo $table_name === 'help' ? ', ' . h($point) : ''; ?>);" class="c-button c-button--bg-green edit"><i class="fa-solid fa-pen"></i></button>
                  <a class="c-button c-button--bg-red delete" id="<?php echo 'item' . h(($id)); ?>" onclick="deleteConfirm('<?php echo h($name); ?>', '<?php echo 'item' . h($id); ?>')" href="./delete.php?id=<?php echo h($id); ?>&from=item-edit&table_number=<?php echo h($editItem); ?>"><i class="fa-regular fa-trash-can"></i></a>
                <?php endif; ?>
            </tr>
          <?php
          endwhile;

          if ($count === 0) : ?>
            <tr class="nodata">
              <td colspan="2">データがありません</td>
            </tr>
        <?php endif;
        else :
          header("Location: ./index.php");
        endif;
        ?>
      </table>
    </div>
  </section>

  <section class="p-section p-section__category-edit">

    <form class="p-form p-form--cat-add" id="itemAddElement" action="./item_add.php" method="POST">
      <h2 class="c-text c-text__subtitle">【カテゴリーを追加】</h2>
      <input type="hidden" name="editItem" value="<?php echo $editItem ?>">
      <div class="p-form__vertical-input">
        <p>項目名<span>※スペースのみ不可</span></p>
        <input type="text" class="item-operate-name" id="name" name="name" value="" pattern="\S|\S.*?\S" required>
      </div>
      <?php if ($table_name === "help") : ?>
        <div class="p-form__vertical-input">
          <p>ポイント数<span>※数値のみ</span></p>
          <input type="number" class="item-operate-name" id="point" name="point" required>
        </div>
      <?php endif; ?>
      <input class="c-button c-button--bg-blue" type="submit" name="add" value="追加">
    </form>

    <form class="p-form p-form--cat-edit" id="itemEditElement" action="./item-update.php" method="POST">
      <h2 class="c-text c-text__subtitle">【カテゴリーを更新】</h2>
      <input type="hidden" name="id" id="updateId" value="">
      <input type="hidden" name="editItem" value="<?php echo $editItem; ?>">
      <div class="p-form__vertical-input">
        <p>項目名<span>※スペースのみ不可</span></p>
        <input type="text" id="updateName" class="item-operate-name" name="name" pattern="\S|\S.*?\S" required>
      </div>
      <?php if ($table_name === "help") : ?>
        <div class="p-form__vertical-input">
          <p>ポイント数<span>※数値のみ</span></p>
          <input type="number" class="item-operate-name" id="updatePoint" name="point" required>
        </div>
      <?php endif; ?>
      <input class="c-button c-button--bg-blue" type="submit" value="更新">
      <a class="c-button c-button--bg-gray" href="">キャンセル</a>
    </form>

  </section>

  <section class="p-section">
    <h2 class="c-text c-text__subtitle">【他のカテゴリーを編集】</h2>
    <div class="p-section__other-catbutton">
      <?php
      if ($select === "adult") :
        $item_name = ["支出カテゴリー", "収入カテゴリー", "支払い方法", "クレジットカード", "スマホ決済", "お手伝い"];
      elseif ($select === "child") :
        $item_name = ["支出カテゴリー", "収入カテゴリー", "支払い方法", "クレジットカード", "スマホ決済"];
      endif;
      for ($i = 0; $i < count($item_name); $i++) : ?>
        <a class="c-button c-button--bg-lightblue" href="./item-edit.php?editItem=<?php echo $i; ?>"><?php echo $item_name[$i]; ?></a>
      <?php endfor; ?>
    </div>
  </section>

  <section class="p-section p-section__back-home">
    <a href="./index.php" class="c-button c-button--bg-gray">ホームに戻る</a>
  </section>
</main>

<?php
//ディレクトリ直下の場合
$footer_back = "on"; //login.php以外に記述
include_once("./component/common/footer.php");
?>

<script src="./js/import.js"></script>
<script src="./js/functions.js"></script>
</body>

</html>