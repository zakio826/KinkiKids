<?php
require_once("./component/common/dbconnect.php");
include_once("./component/common/functions.php");
include_once("./component/common/session.php");
include_once("./component/index/session-param-handler.php");

// 子供用ユーザーの場合
if ($select === "child") {
  include_once("./component/common/send_msg.php");
}

$page_title = "ホーム";
include_once("./component/common/header.php");
?>

<main class="l-main" style="margin-bottom: 120px;">
  <!-- <div class="background"> -->
  <?php
  include_once("./component/index/data-operation.php");
  include_once("./component/index/calendar-detail.php");
  ?>

  <!-- ホーム画面のhtml -->
  <?php
  include_once("./component/index/top.php");
  ?>

  <!-- 銀行画面のhtml -->
  <?php
  include_once("./component/index/bank.php");
  ?>

  <!-- ふりかえり画面のhtml -->
  <div class="u-flex-box records-input-calendar">
    <?php
    include_once("./component/index/review.php");
    ?>
  </div>

  <!-- 設定画面のhtml -->
  <section class="p-section p-section__records-input js-switch-content fade-in hide" data-tab="tab-4">
    <?php
    include_once("./component/index/setting.php");
    ?>
  </section>

  <!-- 家計簿画面のhtml -->
  <div class="u-flex-box records-input-calendar">
    <?php
    include_once("./component/index/household.php");
    ?>
  </div>

  <!-- お手つだいボタンのhtml(ミッション、報酬) -->
  <?php
  include_once("./component/index/mission.php");
  ?>

  <!-- スマホ画面のメニューバーのhtml -->
  <?php
  include_once("./component/index/sp-tab.php");
  ?>
  <!-- </div> -->
</main>

<?php
// ディレクトリ直下の場合
$footer_back = "on"; //login.php以外に記述
include_once("./component/common/footer.php");
?>

<div class="p-back-top" id="page_top" style="margin-bottom: 18px;">
  <a href="#page-top"></a>
</div>

<?php
include_once("./component/common/js.php");
?>
</body>

</html>