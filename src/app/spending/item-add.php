<!-- ヘッダー -->
<?php
$page_title = "カテゴリ編集";
$stylesheet_name = "item_add.css";
include("../include/header.php");
require_once($absolute_path."lib/functions.php");

$user_id = $_SESSION['user_id']; 
$family_id = $_SESSION['family_id'];
?>

<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<?php
  $name_error = "";
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(empty($_POST['name'])){
      switch($_GET['category']){
        case 'spend':
          $_SESSION['category_name_error'] = "*支出カテゴリー名を入力してください。";
          break;
        case 'income':
          $_SESSION['category_name_error'] = "*収入カテゴリー名を入力してください";
          break;
        case 'payment':
          $_SESSION['category_name_error'] = "*支払方法を入力してください";
          break;
      }
      

    }else{

      try {
        $category = $_GET['category']; 
        switch ($category) {
          case 'spend':
            $sql = "INSERT INTO income_expense_category(user_id,family_id,income_expense_category_name,income_expense_flag) VALUES (:user_id, :family_id, :name, 1)";
            break;
          case 'income':
            $sql = "INSERT INTO income_expense_category(user_id,family_id,income_expense_category_name,income_expense_flag) VALUES (:user_id, :family_id, :name, 0)";
            break;
          case 'payment':
            $sql = "INSERT INTO payment(user_id,family_id,payment_name) VALUES (:user_id, :family_id, :name)";
            break;
        }
        $name = $_POST['name'];
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':family_id', $family_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        if ($stmt->execute()) {
          echo "データが正常に登録されました。";
          header("Location: ".$absolute_path."src/app/spending/item-edit.php"); exit;
        } else {
            echo "データの登録中にエラーが発生しました。";
        }
      } catch (PDOException $e) {
        echo "データの登録に発生しました。";
      }

    }

  
  }
?>
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

  <main class="l-main">
    <?php
      if(isset($_GET['category'])) { 
        $category = $_GET['category']; 
      } else {
        header("Location: ".$absolute_path."src/app/spending/item-edit.php"); exit;
      }

      switch ($category) {
        case 'spend':
          $subtitle = "支出カテゴリー";
          break;
        case 'income':
          $subtitle = "収入カテゴリー";
          break;
        case 'payment':
          $subtitle = "支払い方法";
          break;
      }
    ?>
    <h2 class="c-text c-text__subtitle">【<?php echo $subtitle?>編集】</h2>


  <section class="p-section p-section__category-edit">

    <form class="p-form p-form--cat-add" id="itemAddElement" action="" method="POST">
      <h2 class="c-text c-text__subtitle">【カテゴリーを追加】</h2>
      <div class="p-form__vertical-input">
        <p>項目名<span>※スペースのみ不可</span></p>
        <input type="hidden" name="category" value="<?php echo $category ?>">
        <input type="text" class="item-operate-name" id="name" name="name" value="" pattern="\S|\S.*?\S">
      </div>
      <?php
            if(isset($_SESSION['category_name_error'])){
                echo '<p class="name-error">' . $_SESSION['category_name_error'] . '</p>';
                unset($_SESSION['category_name_error']);
            }
            ?>
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