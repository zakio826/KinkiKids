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
    header("Location: ../index.php");
    exit();
endif;
if(isset($_SESSION['updated'])) {
    echo '<script>alert("借金の返済をしました");</script>' ;
    unset($_SESSION['updated']);
}

if(isset($_SESSION['debt'])) {
    echo '<script>alert("' . $_SESSION['debt'] . '円の借り入れを申請しました");</script>' ;
    unset($_SESSION['debt']);
}

$repayment = $debt->display_consent_repayment($user_id);
?>


<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>

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
            <input type="int" name="debt_amount" required>
            <?php 
            // if(isset($_SESSION['debt_error'])) {
            //     echo '<p class="debt_error">' . $_SESSION['debt_error'] . '</p>';
            //     unset($_SESSION['debt_error']);
            // } 
            ?>
        </div>
        <div class="control">
            <label for="installments">何回にわけてかえす？</label>
            <input type="text" name="installments" required>
        </div>
        <div class="control">
            <label for="repayment_date">いつかえす？</label>
            <input type="int" name="repayment_date" placeholder="日付をにゅうりょく" required>
        </div>
        <button type="submit" class="btn-kariru">お金をかりる</button>
    </form>

    <?php 
    if (!empty($repayment)) {
        echo '<h2>借金返済</h2>';
        echo '<ul>';
        foreach ($repayment as $repayment_data) {
            echo '<li>';
            echo '<strong>内容:</strong> ' . $repayment_data['contents'] . '<br>';
            echo '<strong>借りた金額:</strong> ' . $repayment_data['debt_amount'] . '<br>';
            echo '<a href="repayment.php?debt_id=' . $repayment_data['debt_id'] . '">借金返済する</a>';
            echo '</li>';
        }
        echo '</ul>';
    }
    ?>
</div>
</main>
<script src="<?php echo $absolute_path; ?>static/js/debt.js"></script>

<<<<<<< HEAD
<!-- フッター -->
<?php include_once("../include/footer.php"); ?>
=======
<!-- ナビゲーションバー -->
<?php include_once("../include/bottom_nav.php") ?>

<?php require_once("../include/footer.php"); ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var currentDateElement = document.getElementById('currentDate');
        var currentDate = new Date();

        var formattedDate = currentDate.getFullYear() + '/' + (currentDate.getMonth() + 1) + '/' + currentDate.getDate();

        currentDateElement.textContent = formattedDate;
    });
</script>
<?php require_once("../include/footer.php"); ?>
>>>>>>> origin/UIgroup
