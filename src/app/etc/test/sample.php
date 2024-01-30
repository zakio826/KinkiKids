<?php
require_once("../component/common/dbconnect.php");
include_once("../component/common/functions.php");
include_once("../component/common/session.php");
include_once("../component/index/session-param-handler.php");

$page_title = "ホーム";
include_once("../component/common/header.php");
?>

<main class="l-main">
    <div class="background">
        <?php
        include_once("../component/index/data-operation.php");
        include_once("../component/index/calendar-detail.php");
        ?>

        <?php
        include_once("../component/index/top.php");
        ?>

        <div class="u-flex-box records-input-calendar">
            <?php
            include_once("../component/index/household.php");
            ?>
        </div>

        <?php
        include_once("../component/index/mission.php");
        ?>


        <?php
        include_once("../component/index/bank.php");
        ?>

        <section class="p-section p-section__records-input js-switch-content fade-in hide" data-tab="tab-5">
            <?php
            include_once("../component/index/setting.php");
            ?>
        </section>

        <?php
        include_once("../component/index/sp-tab.php");
        ?>
    </div>
</main>

<?php
//ディレクトリ直下の場合
$footer_back = "on"; //login.php以外に記述
include_once("../component/common/footer.php");
?>

<div class="p-back-top" id="page_top">
    <a href="#page-top"></a>
</div>

<?php
include_once("../component/common/js.php");
?>
</body>

</html>