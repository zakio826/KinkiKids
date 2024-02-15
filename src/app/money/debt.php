<?php
$page_title = "銀行";
$stylesheet_name = "debt.css";
require_once("../include/header.php");
?>
<?php
require($absolute_path."lib/debt_class.php");
$user_id = $_SESSION["user_id"];
$family_id = $_SESSION["family_id"];
$debt = new debt($db, $user_id, $family_id);

$select = $_SESSION["select"];


if ($select !== 'child'):
    header("Location: ../point/consent.php");
    exit();
endif;


if(isset($_SESSION['debt'])) {
    echo '<script>alert("' . $_SESSION['debt'] . '円の借り入れを申請しました");</script>' ;
    unset($_SESSION['debt']);
}

?>

<?php include_once("../include/nav_bar.php") ?>

<main>
<div class="mb-3 title"><h1>ぎんこう</h1></div>
<div class ="mb-3 content">
    
    <p id="currentDate"></p>
    <form action="" method="POST">
    
        <div class="control">
            <label for="contents">なににつかう？</label>
            <input type="text" name="contents" required>
        </div>
        <div class="control">
            <label for="debt_amount">どれだけかりる？</label>
            <input type="number" name="debt_amount" required>
        </div>
        <div class="control">
            <label for="installments">何回にわけてかえす？</label>
            <input type="number" min="1" max="24" name="installments" required placeholder="※24回以内">
        </div>
        <div class="control">
            <label for="repayment_date">いつかえす？</label>
            <input type="date" name="repayment_date" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d',strtotime('last day of next month')); ?>" required>
            <p class="note"><b>※来月末以内</b></p>
        </div>
        <button type="submit" class="btn-kariru">お金をかりる</button>
    </form>
</div>
</main>
<script src="<?php echo $absolute_path; ?>static/js/debt.js"></script>

<!-- フッター -->
<?php include_once("../include/bottom_nav.php") ?>
<?php require_once("../include/footer.php"); ?>
