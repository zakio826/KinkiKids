<?php
// session_start();
require_once("./component/common/dbconnect.php");
include_once("./component/common/functions.php");
include_once("./component/common/session.php");

$today = new DateTime();

//初回ログイン情報処理
if (isset($_SESSION["login_times"]) && $_SESSION["login_times"] === "first") :
  $login_times = "first";
else :
  $login_times = "not_first";
endif;

if (isset($_SESSION["hasChild"]) && $_SESSION["hasChild"]) :
  $hasChild = $_SESSION["hasChild"];
else :
  $hasChild = false;
endif;

if (isset($_GET["select"])) {
  $select = $_GET["select"];
} else {
  $select = null;
}

if (isset($_GET["select"]) && $_GET["select"] == "adult") {
  echo "保護者用";
}
if (isset($_GET["select"]) && $_GET["select"] == "child") {
  echo "子ども用";
}

$login = "";
//ログインボタン押下処理
if ($_SERVER["REQUEST_METHOD"] === "POST") :
  $post_username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
  $post_password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

  if (isset($_COOKIE["first-login"])) {
    $login_count = $_COOKIE["first-login"]++;
    setcookie("first-login", $login_count);
  } else {
    setcookie("first-login", 1);
  }

  if (isset($_GET["select"]) && $_GET["select"] === "adult") {
    //ログイン確認
    // $sql = "SELECT * FROM user WHERE username = ? LIMIT 1";
    $sql = "SELECT id, password FROM user WHERE username = ? LIMIT 1";
    $stmt = $db->prepare($sql);

    $stmt->bind_param("s", $post_username);
    sql_check($stmt, $db);

    // $stmt->bind_result($user_id, $email, $username, $hash_password, $initial_savings, $age, $first_name, $family_name, $child_count, $family_id);
    $stmt->bind_result($user_id, $hash_password);
    $stmt->fetch();

    //パスワード一致確認処理
    if (password_verify($post_password, $hash_password)) : //ログイン成功時
      session_regenerate_id();
      $_SESSION["user_id"] = $user_id;
      $_SESSION["select"] = "adult";

      $_SESSION["email"] = $email;
      $_SESSION["username"] = $username;
      $_SESSION["family_name"] = $family_name;
      $_SESSION["first_name"] = $first_name;
      $_SESSION["age"] = $age;
      $_SESSION["initial_savings"] = $initial_savings;
      $_SESSION["family"] = $family_id;

      if (isset($_POST["to_login"])) :
        header("Location: ./index.php");
        exit();
      elseif (isset($_POST["to_setting"])) :
        header("Location: ./join/child_register.php");
        exit();
      endif;
      // echo "ログイン成功";
      exit();

    //ログイン失敗時
    else :
      $login = "error";
    endif;
  }

  if (isset($_GET["select"]) && $_GET["select"] === "child") {
    //ログイン確認
    // $sql = "SELECT * FROM child WHERE login_id = ? LIMIT 1";
    $sql = "SELECT id, password FROM child WHERE login_id = ? LIMIT 1";
    $stmt = $db->prepare($sql);

    $stmt->bind_param("s", $post_username);
    sql_check($stmt, $db);

    // $stmt->bind_result($child_id, $name, $age, $hash_password, $birthday, $parent, $sex, $child_name, $login_id, $family_id, $points, $login_date, $first_date, $savings, $review_date, $review_flag, $max_lending);
    $stmt->bind_result($child_id, $hash_password);
    $stmt->fetch();
    $stmt->close();

    //パスワード一致確認処理
    if (password_verify($post_password, $hash_password)) : //ログイン成功時
      session_regenerate_id();
      $_SESSION["child_id"] = $child_id;
      $_SESSION["hash_password"] = $hash_password;
      $_SESSION["select"] = "child";

      $_SESSION["name"] = $name;
      $_SESSION["age"] = $age;
      $_SESSION["birthday"] = $birthday;
      $_SESSION["parent"] = $parent;
      $_SESSION["sex"] = $sex;
      $_SESSION["child_name"] = $child_name;
      $_SESSION["login_id"] = $login_id;
      $_SESSION["family"] = $family_id;
      $_SESSION["points"] = $points;
      $_SESSION["savings"] = $savings;
      $_SESSION["max_lending"] = $max_lending;
      $_SESSION["review_date"] = $review_date;
      $_SESSION["review_flag"] = $review_flag;

      $select = array(
        "login_date",
      );

      $where = array(
        "id" => ["=", "i", $child_id],
      );

      $result = select($db, $select, "child", wheres: $where);

      if ($result[0]["login_date"] == $today->format("Y-m-d")) {
        $_SESSION["login_count"] = $result[0]["login_date"];
      }

      $update = array(
        "login_date" => ["s", $today->format("Y-m-d")],
      );

      $update_where = array(
        "id" => ["=", "i", $child_id],
      );

      update($db, $update, "child", wheres: $update_where);

      if (isset($_POST["to_login"])) :
        header("Location: ./index.php");
        exit();
      endif;
      // echo "ログイン成功";
      exit();
    //ログイン失敗時
    else :
      $login = "error";
    endif;
  }
endif;

echo $hasChild;
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="//fonts.googleapis.com">
  <link rel="preconnect" href="//fonts.gstatic.com" crossorigin>
  <link href="//fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./css/style.min.css">
  <link rel="preconnect" href="//fonts.googleapis.com">
  <link rel="preconnect" href="//fonts.gstatic.com" crossorigin>
  <link href="//fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&family=Zen+Maru+Gothic:wght@400;500;700;900&display=swap" rel="stylesheet">
  <script src="./js/footer-fixed.js"></script>
  <title>金記キッズ｜ログイン</title>
</head>

<body id="body" class="body">
  <header class="l-header--join">
    <h1 class="l-header__title l-header__title--join">金記キッズ ログイン</h1>
  </header>

  <main class="l-main">
    <?php if (isset($_GET["dataOperation"]) && $_GET["dataOperation"] === "error") : ?> <section class="p-section p-section__full-screen" id="doneOperateBox">
        <div class="p-message-box line-red">
          <p id="doneText"> 無効なURLです </p>
          <button class="c-button c-button--bg-darkred" onclick="onClickOkButton('');">OK</button>
        </div>
      </section>
    <?php endif; ?>

    <?php if ($login === "error") : ?>
      <section class="p-section p-section__message p-section__message--join">
        <div class="p-message-box p-message-box--error">
          <p>ユーザー名またはパスワードが間違えています。</p>
        </div>
      </section>
    <?php endif; ?>
    <section id="login-select" class="p-section p-section__full-screen" <?php echo isset($_GET["select"]) ? "hidden" : ""; ?>>
      <div class="p-detail-login">
        <form action="" method="POST">
          保護者用
          <a name="adult" class="c-button c-button--bg-blue" href="./login.php?select=adult" onclick="login_select();">保護者用ログインページへ</a>
          子ども用
          <a name="child" class="c-button c-button--bg-blue" href="./login.php?select=child" onclick="login_select();">子ども用ログインページへ</a>
        </form>
        <a href="./password-reset/index.php" class="c-link" style="font-size: 1.6rem;">パスワードをお忘れの場合</a>
        <?php if ($login_times === "not_first") : ?>
          <p>ユーザー登録がお済みでない方</p>
          <a class="c-button c-button--bg-blue" href="./join/index.php">新規ユーザー登録</a>
        <?php endif; ?>
      </div>
    </section>

    <section class="p-section p-section__login">
      <form class="p-form p-form--login" action="" method="POST">
        <?php if (!isset($_GET["select"]) || (isset($_GET["select"]) && $_GET["select"] === "adult")) : ?>
          <div class="p-form__vertical-input">
            <p>ユーザー名<span>※半角英数字6〜12文字</span></p>
            <input type="text" name="username" id="username1" onkeyup="usernameChange(1);inputCheck('login');" autocomplete="off" minlength="6" maxlength="12" pattern="[0-9a-zA-Z_]+$" value="" required>
            <p class="username-check" id="usernameCheck1"></p>
          </div>
          <div class="p-form__vertical-input">
            <p>パスワード<span>※半角英数字6〜12文字</span></p>
            <input type="password" name="password" id="password1" onkeyup="passChange('login', 1);inputCheck('login');" autocomplete="off" minlength="6" maxlength="12" pattern="[0-9a-zA-Z]+$" required>
            <p class="pass-check" id="passCheck1"></p>
          </div>
        <?php endif; ?>
        <?php if (isset($_GET["select"]) && $_GET["select"] === "child") : ?>
          <div class="p-form__vertical-input">
            <p>ログインID<span></span></p>
            <input type="text" name="username" id="child_name1" onkeyup="child_nameChange(1);childInputCheck('login');" autocomplete="off" minlength="6" pattern="(?=.*?[\u30A1-\u30FC])[\u30A1-\u30FC\s]*[0-9a-zA-Z]+$" value="" required>
            <p class="username-check" id="child_nameCheck1"></p>
          </div>
          <div class="p-form__vertical-input">
            <p>パスワード<span>※数字4文字</span></p>
            <input type="password" name="password" id="childPassword" onkeyup="childPassChange('login');childInputCheck('login');" autocomplete="off" minlength="4" pattern="[0-9]+$" required>
            <p class="pass-check" id="childPassCheck"></p>
          </div>
        <?php endif; ?>
        <?php if (($select == "adult" && $hasChild == true) || $select == "child" || $login_times === "not_first") : ?>
          <input id="submitButton" class="c-button c-button--bg-blue" type="submit" name="to_login" value="ログイン" disabled>
        <?php endif; ?>
        <?php if ($select == "adult" && $hasChild == false && $login_times === "first") : ?>
          <input id="submitButton" class="c-button c-button--bg-blue" type="submit" name="to_setting" value="初期設定へ" disabled>
        <?php endif; ?>
      </form>
      <a href="./login.php" class="c-button c-button--bg-gray">戻る</a>
    </section>
  </main>

  <?php
  //ディレクトリ直下の場合
  $footer_back = "";
  include_once("./component/common/footer.php");
  ?>
  <script src="./js/import.js"></script>
  <script src="./js/functions.js"></script>
  <script src="./js/input-check.js"></script>
  <script src="./js/child_input_check.js"></script>
</body>

</html>