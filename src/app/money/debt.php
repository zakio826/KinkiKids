<?php
$page_title = "";
$stylesheet_name = "";
require_once("../include/header.php");
?>
<?php
require($absolute_path."lib/debt_class.php");
$exchange = new debt($db);

$select = $_SESSION["select"];

if ($select !== 'child'):
    header("Location: ../index.php");
    exit();
endif;


if(isset($_SESSION['debt'])) {
    echo '<script>alert("' . $_SESSION['debt'] . '円の借り入れを申請しました");</script>' ;
    unset($_SESSION['debt']);
}
?>

<main>
    <p id="currentDate"></p>
    <form action="" method="POST">
        <div class="control">
            <label for="contents">なににつかう？</label>
            <input type="text" name="contents" required>
        </div>
        <div class="control">
            <label for="debt_amount">どれだけかりる？</label>
            <input type="int" name="debt_amount" required>
        </div>
        <div class="control">
            <label for="installments">何回にわけてかえす？</label>
            <input type="text" name="installments" required>
        </div>
        <div class="control">
            <label for="reason">りゆう</label>
            <input type="text" name="reason" required>
        </div>
        <div class="control">
            <label for="repayment_date">いつかえす？</label>
            <input type="int" name="repayment_date" placeholder="日付をにゅうりょく" required>
        </div>
        <button type="submit">お金をかりる</button>
    </form>
</main>

<script>
        document.addEventListener('DOMContentLoaded', function () {
            var currentDateElement = document.getElementById('currentDate');
            var currentDate = new Date();

            var formattedDate = currentDate.getFullYear() + '/' + (currentDate.getMonth() + 1) + '/' + currentDate.getDate();

            currentDateElement.textContent = formattedDate;
        });
    </script>
<?php require_once("../include/footer.php"); ?>