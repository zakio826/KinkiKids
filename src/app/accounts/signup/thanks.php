<?php
session_start();

if (!isset($_SESSION["login_times"]) || !$_SESSION["login_times"] === "first") :
  header("Location: ../login.php");
endif;
// if (!isset($_SESSION["hasChild"]) || !$_SESSION["hasChild"] === "true") :
//   $hasChild = "false";
//   if (!isset($_SESSION["login_times"]) || !$_SESSION["login_times"] === "first") :
//     header("Location: ../login.php");
//   endif;
// else :
//   $hasChild = "true";
//   unset($_SESSION["hasChild"]);

//   if (isset($_POST["to_login"])) :
//     header("Location: ./index.php");
//     exit();
//   elseif (isset($_POST["to_setting"])) :
//     header("Location: ./item-edit.php?editItem=1");
//     exit();
//   endif;
// endif;

// else:
//   header("Location: ../index.php");
if (isset($_SESSION["family_id"])) :
  $family_id = $_SESSION["family_id"];
endif;

$page_title = "登録完了";
include_once("./header.php");
?>
<main class="l-main">
  <section class="p-section p-section__thanks">
    <div class="p-message-box p-message-box--desc">
      <p>ユーザー登録が完了しました</p>
      <a class="c-button c-button--bg-blue" href="../login.php">ログイン画面へ</a>
      <?php //if ($hasChild === "false") : ?>
        <!-- <a class="c-button c-button--bg-blue" href="../login.php">ログイン画面へ</a> -->
        <!-- <input id="submitButton" class="c-button c-button--bg-blue" type="submit" name="to_login" value="ログイン" disabled> -->
      <?php //endif; ?>
      <?php //if ($hasChild === "true") : ?>
        <!-- <a class="c-button c-button--bg-blue" href="../index.php">ホーム画面へ</a> -->
        <!-- <input id="submitButton" class="c-button c-button--bg-blue" type="submit" name="to_setting" value="初期設定へ" disabled> -->
      <?php //endif; ?>
    </div>
  </section>
</main>

<?php
$footer_back = "off";
include_once("../component/common/footer.php");
?>
</body>

</html>