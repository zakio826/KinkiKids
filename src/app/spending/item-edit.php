<!-- ヘッダー -->
<?php
$page_title = "カテゴリ編集";
$stylesheet_name = "item_edit.css";
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
  
  <div class="item_edit_waku2">
    <h2 class="c-text c-text__subtitle">【支出カテゴリー編集】</h2>

<<<<<<< HEAD
  <main class="l-main">
    <div class="item_edit_waku2">
      <h2 class="c-text c-text__subtitle">【支出カテゴリー編集】</h2>

      <div class="item_edit_waku">
        <section class="p-section p-section__category-table">
          <div class="item_edit_moji">
            <table class="p-table p-table--category">
              <tr class="p-table__head">
                <th>項目</th>
                <th>操作</th>
              </tr>

              <?php
                // $table_list = ['spending_category', 'income_category', 'payment_method', 'creditcard', 'qr'];
                // $table_name = $table_list[$editItem];
                // if (in_array($table_name, $table_list) !== false) :
                  $stmt = $db->prepare('SELECT user_id,income_expense_category_id, income_expense_category_name FROM income_expense_category WHERE (user_id = 31 OR user_id = :user_id) AND income_expense_flag = 1');
                  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                  sql_check($stmt, $db);
                  $stmt->execute();
                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
                ?>
                    <tr class="p-table__item">
                      <td><?php echo h($row['income_expense_category_name']); ?></td>
                      <td>
                      <?php if ($row['user_id'] == 31) : ?>
                        操作不可
                      <?php else : ?>
                        <!-- <a id="<?php echo 'item' . h($row['income_expense_category_id']); ?>" onclick="deleteConfirm('<?php echo h($row['income_expense_category_name']); ?>','<?php echo 'item' . h($row['income_expense_category_id']); ?>');">削除</a> -->
                        <a class='c-button c-button--bg-red delete' id="<?php echo h($row['income_expense_category_id']); ?>" onclick="deleteConfirm('<?php echo h($row['income_expense_category_name']); ?>')" href=''>
                        削除<i class="fa-regular fa-trash-can"></i></a>

                        <?php endif; ?>
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
          <!-- <a href="./item-add.php" class="btn-1">追加</a>      -->
          <!-- <input class="c-button c-button--bg-blue" type="submit" name="add" value="【項目を追加】"> -->
        </form>

      </section>
      <button onclick="location.href='https://kinkikids.sub.jp/src/app/spending/item-add.php?category=spend'" class="btn-1">項目を追加</button>
      </div>

      <h2 class="c-text c-text__subtitle">【収入カテゴリー編集】</h2>

      <div class="item_edit_waku">
=======
    <div class="item_edit_waku">
>>>>>>> origin/UIgroup
      <section class="p-section p-section__category-table">
        <div class="item_edit_moji">
          <table class="p-table p-table--category">
            <tr class="p-table__head">
              <th>項目</th>
              <th>操作</th>
            </tr>

            <?php
              // $table_list = ['spending_category', 'income_category', 'payment_method', 'creditcard', 'qr'];
              // $table_name = $table_list[$editItem];
              // if (in_array($table_name, $table_list) !== false) :
                $stmt = $db->prepare('SELECT user_id,income_expense_category_id, income_expense_category_name FROM income_expense_category WHERE (user_id = 31 OR user_id = :user_id) AND income_expense_flag = 1');
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                sql_check($stmt, $db);
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
              ?>
                  <tr class="p-table__item">
                    <td><?php echo h($row['income_expense_category_name']); ?></td>
                    <td>
                    <?php if ($row['user_id'] == 31) : ?>
                      操作不可
                    <?php else : ?>
                      <!-- <a id="<?php echo 'item' . h($row['income_expense_category_id']); ?>" onclick="deleteConfirm('<?php echo h($row['income_expense_category_name']); ?>','<?php echo 'item' . h($row['income_expense_category_id']); ?>');">削除</a> -->
                      <a class='c-button c-button--bg-red delete' id="<?php echo h($row['income_expense_category_id']); ?>" onclick="deleteConfirm('<?php echo h($row['income_expense_category_name']); ?>')" href=''>
                      削除<i class="fa-regular fa-trash-can"></i></a>

                      <?php endif; ?>
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
        <a href="./item-add.php" class="btn-1">追加</a>     
        <!-- <input class="c-button c-button--bg-blue" type="submit" name="add" value="【項目を追加】"> -->
      </form>

    </section>
    </div>

    <h2 class="c-text c-text__subtitle">【収入カテゴリー編集】</h2>

    <div class="item_edit_waku">
    <section class="p-section p-section__category-table">
      <div class="item_edit_moji">
        <table class="p-table p-table--category">
          <tr class="p-table__head">
            <th>項目</th>
            <th>操作</th>
          </tr>

          <?php
            // $table_list = ['spending_category', 'income_category', 'payment_method', 'creditcard', 'qr'];
            // $table_name = $table_list[$editItem];
            // if (in_array($table_name, $table_list) !== false) :
              $stmt = $db->prepare('SELECT user_id,income_expense_category_id, income_expense_category_name FROM income_expense_category WHERE (user_id = 31 OR user_id = :user_id) AND income_expense_flag = 0');
              $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
              sql_check($stmt, $db);
              $stmt->execute();
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
            ?>
                <tr class="p-table__item">
                  <td><?php echo h($row['income_expense_category_name']); ?></td>
                  <td>
                  <?php if ($row['user_id'] == 31) : ?>
                    操作不可
                  <?php else : ?>
                    <a class='c-button c-button--bg-red delete' id="" href=''>削除<i class="fa-regular fa-trash-can"></i></a>
                  <?php endif; ?>
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
        <!-- <input class="c-button c-button--bg-blue" type="submit" name="add" value="【項目を追加】">   -->
      </form>
    </section>
    <button onclick="location.href='https://kinkikids.sub.jp/src/app/spending/item-add.php'" class="btn-1">項目を追加</button>
    </div>

    <h2 class="c-text c-text__subtitle">【支出い方法編集】</h2>

    <div class="item_edit_waku">
      <section class="p-section p-section__category-table">
        <div class="item_edit_moji">
          <table class="p-table p-table--category">
            <tr class="p-table__head">
              <th>項目</th>
              <th>操作</th>
            </tr>

            <?php
              // $table_list = ['spending_category', 'income_category', 'payment_method', 'creditcard', 'qr'];
              // $table_name = $table_list[$editItem];
              // if (in_array($table_name, $table_list) !== false) :
                $stmt = $db->prepare('SELECT user_id,payment_id,payment_name FROM payment WHERE (user_id = 31 OR user_id = :user_id)');
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                sql_check($stmt, $db);
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
              ?>
                  <tr class="p-table__item">
                    <td><?php echo h($row['payment_name']); ?></td>
                    <td>
                    <?php if ($row['user_id'] == 31) : ?>
                      操作不可
                    <?php else : ?>
                      <a class='c-button c-button--bg-red delete btn-2' id="" href=''>削除<i class="fa-regular fa-trash-can"></i></a>
                    <?php endif; ?>
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
          <!-- <input class="c-button c-button--bg-blue" type="submit" name="add" value="【項目を追加】"> -->
        </form>
      </section>
      <button onclick="location.href='https://kinkikids.sub.jp/src/app/spending/item-add.php?category=income'" class="btn-1">項目を追加</button>
      </div>

      <h2 class="c-text c-text__subtitle">【支出い方法編集】</h2>

      <div class="item_edit_waku">
        <section class="p-section p-section__category-table">
          <div class="item_edit_moji">
            <table class="p-table p-table--category">
              <tr class="p-table__head">
                <th>項目</th>
                <th>操作</th>
              </tr>

              <?php
                // $table_list = ['spending_category', 'income_category', 'payment_method', 'creditcard', 'qr'];
                // $table_name = $table_list[$editItem];
                // if (in_array($table_name, $table_list) !== false) :
                  $stmt = $db->prepare('SELECT user_id,payment_id,payment_name FROM payment WHERE (user_id = 31 OR user_id = :user_id)');
                  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                  sql_check($stmt, $db);
                  $stmt->execute();
                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
                ?>
                    <tr class="p-table__item">
                      <td><?php echo h($row['payment_name']); ?></td>
                      <td>
                      <?php if ($row['user_id'] == 31) : ?>
                        操作不可
                      <?php else : ?>
                        <a class='c-button c-button--bg-red delete btn-2' id="" href=''>削除<i class="fa-regular fa-trash-can"></i></a>
                      <?php endif; ?>
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
            <!-- <input class="c-button c-button--bg-blue" type="submit" name="add" value="【項目を追加】"> -->
          </form>
        </section>
        <button onclick="location.href='https://kinkikids.sub.jp/src/app/spending/item-add.php?category=payment'" class="btn-1">項目を追加</button>  
      </div>

      <section class="p-section p-section__back-home btn-iti">
        <a href="./spending_input.php" class="btn-2">戻る</a>
      </section>
    </div>

    <section class="p-section p-section__back-home btn-iti">
      <a href="./spending_input.php" class="btn-2">戻る</a>
    </section>
  </div>
</main>


<?php include_once("../include/bottom_nav.php") ?>

<!-- JavaScript -->
<script src="<?php echo $absolute_path; ?>static/js/spending_input/radio.js"></script>
<script src="<?php echo $absolute_path; ?>static/js/spending_input/import.js"></script>
<script src="<?php echo $absolute_path; ?>static/js/spending_input/functions.js"></script>


<!-- フッター -->
<?php include_once("../include/footer.php"); ?>