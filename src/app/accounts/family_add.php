<!-- ユーザー登録ページ -->

<!-- ヘッダー -->
<?php
$page_title = "アカウント作成";
$stylesheet_name = "family_add.css";
require_once("../include/header.php");
?>

<?php
// family_addクラスのインスタンスを作成
require($absolute_path."lib/family_add_class.php");
$family_add = new family_add($db);

$errors = $family_add->getError();

if (!isset($_SESSION["admin_flag"]) || $_SESSION["admin_flag"] !== 1) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $family_add->__construct($db);
}
?>

<!-- ナビゲーションバー -->
<?php include_once("../include/nav_bar.php") ?>

<main>
    <div class="content">
        <form action="" method="POST">
            <h1>アカウント追加</h1>
            <p class="mb-3">当サービスをご利用するために、<br>次のフォームに必要事項をご記入ください。</p>
            
            <div class="scrollable-container">
                <div id="userFormsContainer">
                    <div id="userForm" class="control">
                        <!-- ユーザー情報の入力フォーム -->
                        <div class="control">
                            <label for="username">ユーザー名</label>
                            <input type="text" name="username[]"><br>
                            <?php if(isset($errors['username'][0])){
                                    $family_add->username_error($errors['username'][0]);
                            } ?>
                                
                        </div>
                        
                        <div class="control">
                            <label for="password">パスワード</label>
                            <input type="password" name="password[]"><br>
                            <?php if(isset($errors['password'][0])){
                                    $family_add->password_error($errors['password'][0]);
                            } ?>
                        </div>

                        <div class="control">
                            <label for="last_name">名字</label>
                            <input type="text" name="last_name[]"><br>
                            <?php if(isset($errors['last_name'][0])){
                                    $family_add->lastname_error($errors['last_name'][0]);
                            } ?>
                        </div>

                        <div class="control">
                            <label for="first_name">名前</label>
                            <input type="text" name="first_name[]"><br>
                            <?php if(isset($errors['first_name'][0])){
                                    $family_add->firstname_error($errors['first_name'][0]);
                            } ?>
                        </div>

                        <div class="control">
                            <label for="birthday">誕生日</label>
                            <input type="date" name="birthday[]"><br>
                            <?php if(isset($errors['birthday'][0])){
                                    $family_add->birthday_error($errors['birthday'][0]);
                            } ?>
                        </div>

                        <div class="control">
                            <label for="gender_id">性別</label>
                            <select name="gender_id[]">
                                <option value="1">女性</option>
                                <option value="2">男性</option>
                                <option value="3">その他</option>
                            </select>
                        </div>

                        <div class="control">
                            <label for="role_id">役割</label>
                            <select name="role_id[]" class="roleSelect" onchange="toggleSavingsField(this)">
                                <?php $family_add->role_select(); ?>
                            </select>
                        </div>

                        <div class="control" style="display: none;">
                            <label for="savings">貯蓄</label>
                            <input class="mb-3 savings-input" type="int" name="savings[]" value="0">

                            <label for="allowances">お小遣い金額</label>
                            <input class="allowance-input" type="int" name="allowances[]" value="0">

                            <label for="payments">受取日</label>
                            <input class="payment-input" type="int" name="payments[]" value="0">
                        </div>

                        <div class="control">
                            <label for="admin_flag">管理者</label>
                            <input type="checkbox" name="admin_flag[]">
                        </div>
                    </div>
                </div>
            <div class="btn-p"><button type="button" id="addUser" >＋</button></div>
            <div class="control"><button type="submit" class="btn">確認する</button></div>
        </form>
    </div>
</main>

<!-- ナビゲーションバー -->
<?php include_once("./include/bottom_nav.php") ?>

<!-- JavaScript -->
<script src="<?php echo $absolute_path; ?>static/js/family_add.js"></script>

<!-- フッター -->
<?php require_once("../include/footer.php"); ?>