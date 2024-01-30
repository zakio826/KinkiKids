<?php
require_once("./component/common/dbconnect.php");
include_once("./component/common/functions.php");
include_once("./component/common/session.php");
// -- スマホ画面のメニューバーのhtml 
include_once("./component/index/sp-tab2.php");


if ($select === "adult") {
  $page_title = "アカウント管理:保護者";
  $child_count = count($user["child"]);

  $sql = "UPDATE user SET child_count = ? WHERE id = ? ";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("ii", $child_count, $user["id"]);
  sql_check($stmt, $db);
  $stmt->close();

  $_SESSION["parent_id"] = $user["id"];
  $_SESSION["family_id"] = $family_id;
  $_SESSION["from"] = "account";
} elseif ($select === "child") {
  $page_title = "アカウント管理：子ども";

  $parent_col = [
    "family_name",
    "name",
  ];

  $parent_where = [
    "id" => ["=", "i", $user["parent"]],
  ];

  $result = select($db, $parent_col, "user", wheres: $parent_where);
  $parent_name = $result[0]["family_name"] . " " . $result[0]["name"];
}
include_once("./component/common/header.php");
?>

<main class="l-main">
  <?php if (isset($_GET["dataOperation"]) && ($_GET["dataOperation"] === "pwerror" || $_GET["dataOperation"] === "update" || $_GET["dataOperation"] === "error")) : ?>
    <section class="p-section p-section__full-screen" id="doneOperateBox">
      <div class="p-message-box <?php echo ($_GET["dataOperation"] === "error" || $_GET["dataOperation"] === "pwerror") ? "line-red" : "line-blue"; ?>">
        <p id="doneText">
          <?php
          if ($_GET["dataOperation"] === "error") {
            echo "正しく処理されませんでした";
          } elseif ($_GET["dataOperation"] === "pwerror") {
            echo "現在のパスワードが一致しません";
          } elseif ($_GET["dataOperation"] === "update") {
            echo "更新しました";
          }
          ?>
        </p>
        <button class="c-button <?php echo ($_GET["dataOperation"] === "error" || $_GET["dataOperation"] === "pwerror") ? "c-button--bg-darkred" : "c-button--bg-blue"; ?>" onclick="onClickOkButton("");">OK</button>
      </div>
    </section>
  <?php endif; ?>
  <?php if ($_SERVER["REQUEST_METHOD"] === "POST" && (isset($_POST["modify_email"]) || isset($_POST["modify_username"]) || isset($_POST["modify_password"]) || isset($_POST["modify_initial_savings"]) || isset($_POST["modify_age"]) || isset($_POST["modify_child_count"]) || isset($_POST["modify_family_name"]) || isset($_POST["modify_first_name"]))) : ?>
    <?php
    if (isset($_POST["modify_email"])) {
      $item_label = "メールアドレス";
      $column = "email";
      $caution = "";
      $modify_item = $_POST["email"];
    } elseif (isset($_POST["modify_username"])) {
      $item_label = "ユーザー名";
      $column = "username";
      $caution = "※半角英数字6〜12文字";
      $modify_item = $_POST["username"];
    } elseif (isset($_POST["modify_family_name"])) {
      $item_label = "ユーザー名";
      $column = "family_name";
      $caution = "※半角英数字6〜12文字";
      $modify_item = $_POST["family_name"];
    } elseif (isset($_POST["modify_first_name"])) {
      $item_label = "ユーザー名";
      $column = "first_name";
      $caution = "※半角英数字6〜12文字";
      $modify_item = $_POST["first_name"];
    } elseif (isset($_POST["modify_age"])) {
      $item_label = "年齢";
      $column = "age";
      $caution = "※";
      // $modify_item = number_format($_POST["age"]) . "歳";
      $modify_item = $_POST["age"] . "歳";
    } elseif (isset($_POST["modify_password"])) {
      $item_label = "パスワード";
      $column = "password";
      $caution = "※半角英数字6〜12文字";
      $modify_item = "非表示";
    } elseif (isset($_POST["modify_initial_savings"])) {
      $item_label = "初期貯蓄額";
      $column = "initial_savings";
      $caution = "";
      $modify_item = number_format($_POST["initial_savings"]) . "円";
    } elseif (isset($_POST["modify_child_count"])) {
      $item_label = "子どもの数";
      $column = "child_count";
      $caution = "";
      $modify_item = number_format($_POST["child_count"]) . "人";
    }
    ?>
    <section class="p-section p-section__full-screen">
      <form class="p-form p-form--account-edit" action="./account-update.php" method="POST">
        <input type="hidden" name="column_value" value="<?php echo h($column); ?>">
        <div class="p-form__vertical-input">
          <p>現在の<?php echo h($item_label); ?></p>
          <?php if ($column === "password") : ?>
            <input type="password" name="now_password" required>
          <?php elseif ($column === "child_count") : ?>
            <p><?php echo h($modify_item); ?></p>
            <table class="p-table p-table--record-output" style="line-height: normal;">
              <thead>
                <tr>
                  <!-- <td>番号</td> -->
<!-- データベース -->
                  <td>名前</td>
                  <td>性別</td>
                  <td>年齢</td>
                  <td>個別家計簿</td>
                </tr>
              </thead>
              <tbody>
                <?php for ($i = 0; $i < $child_count; $i++) : ?>
                  <tr>
                    <!-- <td><?php //echo $i + 1; ?></td> -->
                    <td><?php echo h($child[$i]["child_name"]); ?></td>
                    <td><?php echo h($child[$i]["sex"]) == 0 ? "男" : "女"; ?></td>
                    <td><?php echo h($child[$i]["age"]) . "歳"; ?></td>
                    <td class="p-form__center">
                      <input type="button" class="c-button c-button--bg-blue individual" value="家計簿へ" onclick="location.href='./show.php'">
                    </td>
                  </tr>
                <?php endfor; ?>
              </tbody>
            </table>
          <?php else : ?>
            <p><?php echo h($modify_item); ?></p>
          <?php endif; ?>
        </div>
        <?php if (!isset($_POST["modify_child_count"])) : ?>
          <div class="p-form__vertical-input">
            <p>新しい<?php echo h($item_label) . h($caution); ?></p>
            <?php if (isset($_POST["modify_email"])) : ?>
              <input type="text" name="modify_value" pattern="^[a-zA-Z0-9.!#$&’*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$" required>
            <?php elseif (isset($_POST["modify_username"])) : ?>
              <input type="text" name="modify_value" minlength="6" maxlength="12" pattern="^[0-9a-zA-Z]+$" required>
            <?php elseif (isset($_POST["modify_family_name"])) : ?>
              <input type="text" name="modify_value" required>
            <?php elseif (isset($_POST["modify_first_name"])) : ?>
              <input type="text" name="modify_value" required>
            <?php elseif (isset($_POST["modify_password"])) : ?>
              <input type="password" name="modify_value" minlength="6" maxlength="12" pattern="^[0-9a-zA-Z]+$" required>
            <?php elseif (isset($_POST["modify_age"])) : ?>
              <input type="number" name="modify_value" required>
            <?php elseif (isset($_POST["modify_initial_savings"])) : ?>
              <input type="number" name="modify_value" required>
            <?php endif; ?>
          <?php endif; ?>
          </div>
          <div class="p-form__center">
            <a class="c-button" href="./account.php">キャンセル</a>
            <?php if ($column === "password") : ?>
              <input class="c-button c-button--bg-blue" type="submit" name="password_modify" value="変更する">
            <?php elseif ($column === "child_count") : ?>
              <input type="button" class="c-button c-button--bg-blue" value="追加する" onclick="location.href='./join/child_register.php'">
              <!-- <input class="c-button c-button--bg-blue" type="submit" name="child_modify" value="追加する"> -->
            <?php else : ?>
              <input class="c-button c-button--bg-blue" type="submit" name="other_modify" value="変更する">
            <?php endif; ?>
          </div>
      </form>
    </section>
  <?php endif; ?>

  <section class="p-section">
    <?php if ($select === "adult") : ?>
      <h2 class="c-text c-text__subtitle">【アカウント管理:保護者】</h2>
    <?php elseif ($select === "child") : ?>
      <h2 class="c-text c-text__subtitle">【アカウント管理:子ども】</h2>
    <?php endif; ?>
    <form class="p-form p-form--account" action="" method="POST" id="account_info1">
      <?php if (isset($_SESSION["select"]) && $_SESSION["select"] === "adult") : ?>


        <div class="info">
          <p>メールアドレス</p>
          <input type="hidden" name="email" id="email" value="<?php echo h($user["email"]); ?>">
          <p><?php echo h($user["email"]) ?></p>
          <input class="c-button c-button--bg-blue" type="submit" name="modify_email" value="変更">
        </div>
        <div class="info">
          <p>ユーザー名</p>
          <input type="hidden" name="username" id="username" value="<?php echo h($user["username"]); ?>">
          <p><?php echo h($user["username"]) ?></p>
          <input class="c-button c-button--bg-blue" type="submit" name="modify_username" value="変更">
        </div>
        <div class="info">
          <p>苗字</p>
          <input type="hidden" name="family_name" id="family_name" value="<?php echo h($user["family_name"]); ?>">
          <p><?php echo h($user["family_name"]); ?></p>
          <input class="c-button c-button--bg-blue" type="submit" name="modify_family_name" value="変更">
        </div>
        <div class="info">
          <p>名前</p>
          <input type="hidden" name="first_name" id="first_name" value="<?php echo h($user["first_name"]); ?>">
          <p><?php echo h($user["first_name"]); ?></p>
          <input class="c-button c-button--bg-blue" type="submit" name="modify_first_name" value="変更">
        </div>
        <!-- <div class="info">
          <p>年齢</p>
          <input type="hidden" name="age" id="age" value="<?php //echo h($age);
                                                          ?>">
          <p><?php //echo h($age) . "歳"
              ?></p>
          <input class="c-button c-button--bg-blue" type="submit" name="modify_age" value="変更">
        </div> -->
        <div class="info">
          <p>パスワード</p>
          <input type="hidden" name="password" id="password">
          <p>セキュリティ上非表示</p>
          <input class="c-button c-button--bg-blue" type="submit" name="modify_password" value="変更">
        </div>
        <!-- <div class="info">
          <p>初期貯蓄額</p>
          <input type="hidden" name="initial_savings" id="initialSavings" value="<?php //echo h($initial_savings);
                                                                                  ?>">
          <p><?php //echo h($initial_savings !== "" ? number_format($initial_savings) . "円" : "未登録");
              ?></p>
          <input class="c-button c-button--bg-blue" type="submit" name="modify_initial_savings" value="変更">
        </div> -->
        <div class="info">
          <p>子どもの数</p>
          <input type="hidden" name="child_count" id="childCount" value="<?php echo h($child_count); ?>">
          <p><?php echo h($child_count !== "" ? number_format($child_count) . "人" : "未登録"); ?></p>
          <input class="c-button c-button--bg-blue" type="submit" name="modify_child_count" value="子どもの詳細">
        </div>
      <?php endif; ?>

      <?php if (isset($select) && $select === "child") : ?>
        <div class="info">
          <p>親</p>
          <input type="hidden" name="parentName" id="parentName" value="<?php echo h($parent_name); ?>">
          <p><?php echo h($parent_name) ?></p>
        </div>
        <div class="info">
          <p>ユーザー名</p>
          <input type="hidden" name="username" id="username" value="<?php echo h($user["name"]); ?>">
          <p id="account_info2"><?php echo h($user["name"]) ?></p>
          <input class="c-button c-button--bg-blue" type="submit" name="modify_username" value="変更">
        </div>
        <div class="info">
          <p>年齢</p>
          <input type="hidden" name="age" id="age" value="<?php echo h($user["age"]); ?>">
          <p id="account_info2"><?php echo h($user["age"]) . "歳" ?></p>
          <input class="c-button c-button--bg-blue" type="submit" name="modify_age" value="変更">
        </div>
        <div class="info">
          <p>誕生日</p>
          <input type="hidden" name="birthday" id="birthday" value="<?php echo h($user["birthday"]); ?>">
          <p id="account_info2"><?php echo h($user["birthday"]) ?></p>
          <input class="c-button c-button--bg-blue" type="submit" name="modify_age" value="変更">
        </div>
        <div class="info">
          <p>パスワード</p>
          <input type="hidden" name="password" id="password">
          <p id="account_info2">セキュリティ上非表示</p>
          <input class="c-button c-button--bg-blue" type="submit" name="modify_password" value="変更">
        </div>
        <div class="info">
          <p>初期貯蓄額</p>
          <input type="hidden" name="initial_savings" id="initialSavings" value="<?php echo h($savings); ?>">
          <p id="account_info2"><?php echo h($savings !== "" ? number_format($savings) . "円" : "未登録"); ?></p>
          <input class="c-button c-button--bg-blue" type="submit" name="modify_initial_savings" value="変更">
        </div>
      <?php endif; ?>
    </form>
  </section>


  <style>
    #account_info1 {
      max-width: 430px;
      /* min-width: 420px; */
    }

    /* #account_info2 {
      width: 20%;
      min-width: 22rem;
    } */
  </style>

  <section class="p-section p-section__back-home">
    <a class="c-button c-button--bg-gray" href="./index.php">ホームに戻る</a>
  </section>
</main>

<?php
//ディレクトリ直下の場合
$footer_back = "on"; //login.php以外に記述
include_once("./component/common/footer.php");
?>

<script src="./js/footer-fixed.js"></script>
<script src="./js/import.js"></script>
<script src="./js/functions.js"></script>
</body>

</html>