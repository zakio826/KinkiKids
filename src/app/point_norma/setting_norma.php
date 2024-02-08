<!-- ポイントノルマ設定ページ -->

<!-- ヘッダー -->
<?php
$page_title = "ポイントノルマ設定";
$stylesheet_name = "setting_norma.css";
include("../include/header.php");
?>

<?php // ページの最初に行う処理
 require($absolute_path."lib/setting_norma_class.php");
 $setting_norma = new setting_norma($db);
?>


<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>  <!-- ←一番外側はmainタグを指定する -->
    <section>
        <div class="content">
            <form action="" method="POST">
                <h1>ポイントノルマ設定</h1>

                <br>

                <div class="control-1">
                    <label for="norma_amount">ポイントノルマ</label>
                    <input id="norma_amount" type="number" name="norma_amount">
                    <b>pt</b>
                    <?php $setting_norma->norma_error(); ?>
                </div>

                <div class="control-1">
                    <label for="point_norma_deadline">期限</label>
                    <input id="point_norma_deadline" type="date" name="point_norma_deadline">
                    <?php $setting_norma->deadline_error(); ?>
                </div>

                <br>

                <div class="control-2">
                    <button type="submit" class="btn">登録</button>
                </div>
            </form>
        </div>
    </section>
</main>
<!-- フッター -->
<?php include_once("../include/footer.php"); ?>