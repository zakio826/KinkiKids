<!-- ヘッダー -->
<?php
$page_title = "カテゴリ編集";
//$stylesheet_name = "spending_input.css";
include("../include/header.php");
require_once($absolute_path."lib/functions.php");
?>

<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<!DOCTYPE html>
<html lang="ja">


<body class="body" id="body">
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
    <h2 class="c-text c-text__subtitle">【支出カテゴリー編集】</h2>


    <section class="p-section p-section__category-table">
      <div>
        <table class="p-table p-table--category">
          <tr class="p-table__head">
            <th>項目</th>
            <th>操作</th>
          </tr>

          <?php
            // $table_list = ['spending_category', 'income_category', 'payment_method', 'creditcard', 'qr'];
            // $table_name = $table_list[$editItem];
            // if (in_array($table_name, $table_list) !== false) :
              $stmt = $db->prepare('SELECT payment_id,payment_name FROM payment WHERE user_id = 31');
              sql_check($stmt, $db);
              $stmt->execute();
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
            ?>
                <tr class="p-table__item">
                  <td><?php echo h($row['payment_name']); ?></td>
                  <td>
                    <button class='c-button c-button--bg-green edit'><i class="fa-solid fa-pen"></i></button>
                    <a class='c-button c-button--bg-red delete' id="" href=''><i class="fa-regular fa-trash-can"></i></a>
                  </td>
                </tr>
            <?php
              endwhile;
            // else :
            //   header('Location: ./index.php');
            // endif;
            ?>
        </table>
      </div>
    </section>

    <section class="p-section p-section__category-edit">

      <form class="p-form p-form--cat-add" id="itemAddElement" action="" method="POST">
        <input type="hidden" name="editItem" value="">     
        <input class="c-button c-button--bg-blue" type="submit" name="add" value="【カテゴリーを追加】">
      </form>

    </section>



    <section class="p-section p-section__back-home">
      <a href="./index.php" class="c-button c-button--bg-gray">ホームに戻る</a>
    </section>
  </main>

  <footer id="footer" class="l-footer">
    <p>家計簿アプリ｜2022</p>
  </footer>
<?php include_once("../include/bottom_nav.php") ?>

<!-- JavaScript -->
<script src="<?php echo $absolute_path; ?>static/js/spending_input/radio.js"></script>
<script src="<?php echo $absolute_path; ?>static/js/spending_input/import.js"></script>
<script src="<?php echo $absolute_path; ?>static/js/spending_input/functions.js"></script>
<!-- フッター -->
<?php include_once("../include/footer.php"); ?>
</body>



</html>