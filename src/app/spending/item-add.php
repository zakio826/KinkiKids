<!-- ヘッダー -->
<?php
$page_title = "カテゴリ編集";
$stylesheet_name = "item_add.css";
include("../include/header.php");
require_once($absolute_path."lib/functions.php");
$user_id = $_SESSION['user_id']; 
?>

<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>


<main class="l-main">
  <header class="l-header">
    <h1 class="l-header__title"><a href="./index.php">家計簿アプリ</a></h1>
    <div class="l-header__icon">
      <a href="./index.php">
        <i class="fa-solid fa-house"></i>
      </a>
      <a href="./account.php">
        <i class="fa-solid fa-user"></i>
      </a>
      <a href="" id="logoutButton" onclick="">
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
      </a>
    </div>
  </header>

  <h2 class="c-text c-text__subtitle">【支出カテゴリー編集】</h2>


  <section class="p-section p-section__category-edit">

    <form class="p-form p-form--cat-add" id="itemAddElement" action="" method="POST">
      <h2 class="c-text c-text__subtitle">【カテゴリーを追加】</h2>
      <input type="hidden" name="editItem" value="">
      <div class="p-form__vertical-input">
        <p>項目名<span>※スペースのみ不可</span></p>
        <input type="text" class="item-operate-name" id="name" name="name" value="" pattern="\S|\S.*?\S" required>
      </div>
      
      <input class="btn-1" type="submit" name="add" value="追加">
    </form>

  </section>

</main>

<?php include_once("../include/bottom_nav.php") ?>

<!-- JavaScript -->
<script src="<?php echo $absolute_path; ?>static/js/spending_input/radio.js"></script>
<script src="<?php echo $absolute_path; ?>static/js/spending_input/import.js"></script>
<script src="<?php echo $absolute_path; ?>static/js/spending_input/functions.js"></script>

<!-- フッター -->
<?php include_once("../include/footer.php"); ?>