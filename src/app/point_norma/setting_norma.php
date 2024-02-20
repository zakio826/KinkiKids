<!-- ポイントノルマ設定ページ -->

<!-- ヘッダー -->
<?php
$page_title = "ポイントノルマ設定";
$stylesheet_name = "setting_norma_all.css";
include("../include/header.php");
?>


<?php // ページの最初に行う処理
 require($absolute_path."lib/setting_norma_class.php");
 $setting_norma = new setting_norma($db);
 $familyId = $_SESSION['join']['family_id'];
?>


<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>  <!-- ←一番外側はmainタグを指定する -->
    <section>
    <div class= <?php echo ($select === 'adult') ? "content_adult" : "content_child" ;?>>
        <form action="" method="POST">
            <div class=<?php echo ($select === 'adult') ? "adult_font" : "child_font" ;?>>
                <h1>ポイントノルマ<ruby>設定<rt>せってい</rt></ruby></h1>
            </div>

            <div class="control-1">
                <label for="norma_amount">ポイントノルマ</label>
                <input id="norma_amount" type="number" name="norma_amount" placeholder="500" value="<?php echo isset($_SESSION['join']['norma_amount']) ? htmlspecialchars($_SESSION['join']['norma_amount'], ENT_QUOTES) : ''; ?>">
                <!-- <b>pt</b> -->
                <?php $setting_norma->norma_error(); ?>
            </div>

            <div class="control-1">
                <label for="norma_user"><ruby>子供<rt>こども</rt></ruby></label>
                <br>
                <select id="norma_user" name="norma_user">
                    <?php
                    // セッションから家族IDを取得
                    $familyId = $_SESSION['join']['family_id'];

                    // 家族IDに基づいてユーザーを取得
                    list($child_id, $child_first_name) = $setting_norma->getFamilyUsers($familyId);
                    $new_user = array_combine($child_id, $child_first_name);
                    // プルダウンメニューにユーザーを表示
                    foreach ($new_user as $key => $value) {
                        echo  '<option value="' . $key . '">' . $value . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="control-1">
                <label for="point_norma_deadline">いつまで？</label>
                <input id="point_norma_deadline" type="date" name="point_norma_deadline" value="<?php echo isset($_SESSION['join']['point_norma_deadline']) ? htmlspecialchars($_SESSION['join']['point_norma_deadline'], ENT_QUOTES) : ''; ?>">
                <?php $setting_norma->deadline_error(); ?>
            </div>

            <div class="control-2">
                <button type="submit" class="btn"><ruby>登録<rt>とうろく</rt></ruby></button>
            </div>
        </form>
    </div>
    </section>
</main>
<!-- ナビゲーションバー -->
<?php include_once("../include/bottom_nav.php") ?>
<!-- フッター -->
<?php include_once("../include/footer.php"); ?>