<?php
session_start();
require_once("../component/common/dbconnect.php");
include_once("../component/common/functions.php");

// if (isset($_SESSION["email"]) && isset($_SESSION["username"]) && isset($_SESSION["password"])/* && isset($_SESSION["age"]) && isset($_SESSION["initial_savings"])*/) :
if (isset($_SESSION["parent"])) :
  // $email = $_SESSION["email"];
  // $username = $_SESSION["username"];
  // $password = $_SESSION["password"];
  // $name = $_SESSION["name"];
  // $age = $_SESSION["age"];
  // $initial_savings = $_SESSION["initial_savings"];

  for ($i = 0; $i < $_SESSION["users"]; $i++) {
    $parent[$i] = array(
      "email" => $_SESSION["parent"][$i]["email"],
      "username" => $_SESSION["parent"][$i]["username"],
      "family_name" => $_SESSION["parent"][$i]["family_name"],
      "first_name" => $_SESSION["parent"][$i]["first_name"],
      "password" => $_SESSION["parent"][$i]["password"],
    );
  }
else :
  header("Location: index.php");
  exit();
endif;

//登録ボタン押下でデータを登録
if ($_SERVER["REQUEST_METHOD"] === "POST") :
  $INTEREST = 1.1;

  /* 利子固定 */
  // $sql = "INSERT INTO family(name) VALUES(?)";
  // $stmt = $db->prepare($sql);
  // $stmt->bind_param("s", $parent[0]["family_name"]);

  /* 利子変動 */
  $sql = "INSERT INTO family(name, interest) VALUES(?, ?)";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("sd", $parent[0]["family_name"], $INTEREST);
  sql_check($stmt, $db);
  $stmt->close();

  $sql = "SELECT id FROM family WHERE name = ? LIMIT 1";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("s", $parent[0]["family_name"]);
  sql_check($stmt, $db);
  $stmt->bind_result($family_id);
  $stmt->fetch();
  $stmt->close();

  $sql = "INSERT INTO user(email, username, password, family_id, name, family_name) VALUES(?, ?, ?, ?, ?, ?)";
  // $sql = "INSERT INTO user(email, username, password, initial_savings, age) VALUES(?, ?, ?, ?, ?)";
  $stmt = $db->prepare($sql);
  for ($i = 0; $i < $_SESSION["users"]; $i++) {
    // $encryption = password_hash($password, PASSWORD_DEFAULT);
    $encryption = password_hash($parent[$i]["password"], PASSWORD_DEFAULT);
    $stmt->bind_param("sssiss", $parent[$i]["email"], $parent[$i]["username"], $encryption, $family_id, $parent[$i]["first_name"], $parent[$i]["family_name"]);
    // $stmt->bind_param("sssii", $email, $username, $encryption, $initial_savings, $age);
    sql_check($stmt, $db);
  }
  $stmt->close();

  $sql = "SELECT id FROM user WHERE username = ? LIMIT 1";
  $stmt = $db->prepare($sql);

  $stmt->bind_param("s", $parent[0]["username"]);
  sql_check($stmt, $db);

  $stmt->bind_result($user_id);
  $stmt->fetch();

  $_SESSION["family_id"] = $family_id;
  $_SESSION["parent_id"] = $user_id;

  unset($_SESSION["email"], $_SESSION["username"], $_SESSION["password"], $_SESSION["name"], $_SESSION["parent"]/*, $_SESSION["age"], $_SESSION["initial_savings"]*/);

  $_SESSION["login_times"] = "first";

  // $select = "SELECT id FROM user WHERE id = ?";
  // $select_stmt = $db->prepare($select);
  // $_SESSION["parent"] = $id;

  header("Location: thanks.php");
endif;

$page_title = "登録情報確認";
include_once("./header.php");
?>

<main class="l-main">
  <section class="p-section p-section__join-confirm">
    <form class="p-form p-form--join" action="" method="post">
      <?php for ($i = 0; $i < $_SESSION["users"]; $i++) : ?>
        <h3>保護者<?php echo $i + 1; ?></h3>
        <div class="p-form__vertical-input">
          <p>メールアドレス</p>
          <p>【<?php echo h($parent[$i]["email"]); ?>】</p>
        </div>
        <div class="p-form__vertical-input">
          <p>ユーザー名</p>
          <p>【<?php echo h($parent[$i]["username"]); ?>】</p>
        </div>
        <!-- <div class="p-form__vertical-input">
        <p>年齢</p>
        <p>【<?php //echo h($age);
            ?>】</p>
      </div> -->
        <div class="p-form__vertical-input">
          <p>パスワード</p>
          <p>【セキュリティ上表示されません】</p>
        </div>
        <!-- <div class="p-form__vertical-input">
        <p>貯蓄額</p>
        <p>【<?php //echo $initial_savings !== "" ? number_format(h($initial_savings)) . "円" : "未登録";
            ?>】</p>
      </div> -->
      <?php endfor; ?>
      <div class="u-flex-box">
        <a class="c-button c-button--bg-gray" href="./index.php?mode=modify">修正する</a>
        <input class="c-button c-button--bg-blue" type="submit" value="登録する">
      </div>
    </form>
  </section>
</main>

<?php
$footer_back = "off";
include_once("../component/common/footer.php");
?>
</body>

</html>