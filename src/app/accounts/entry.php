<!-- ユーザー登録ページ -->

<!-- ヘッダー -->
<?php
$page_title = "アカウント作成";
$stylesheet_name = "login.css";
require_once("../include/header.php");
?>

<?php 
require($absolute_path."lib/entry_class.php");
// entryクラスのインスタンスを作成
$entry = new entry($db);

// フォームが送信されたかどうかを確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entry->__construct($db);
}
?>

<main>
    <div class="content">
        <div class="frame_entry">
            <div class="wrapper1">
                <form action="entry.php" method="POST">
                    <div class="title"><h1>アカウント作成</h1></div>
                    <p>当サービスをご利用するために、<br>次のフォームに必要事項をご記入ください。</p>

                    <br>

                    <div class="scrollable-container">
                        <!-- 「FIXME」管理ユーザーがログインしている場合は表示しないようにする -->
                        <div class="form-group_entry">
                            <label for="family_name">家族名</label>
                            <input id="family_name" type="text" name="family_name" class="form-control_entry"
                            value="<?php echo isset($_SESSION['join']['family_name']) ? htmlspecialchars($_SESSION['join']['family_name'], ENT_QUOTES) : ''; ?>">
                            <?php $entry->familyname_error(); ?>
                        </div>
                        
                        <div class="form-group_entry">
                            <label for="username">ユーザー名</label>
                            <input id="username" type="text" name="username" class="form-control_entry" placeholder="※半角英数字で入力してください"
                            value="<?php echo isset($_SESSION['join']['username']) ? htmlspecialchars($_SESSION['join']['username'], ENT_QUOTES) : ''; ?>">
                            <?php $entry->username_error(); ?>
                        </div>

                        <div class="form-group_entry">
                            <label for="password">パスワード</label>
                            <input id="password" type="password" name="password" class="form-control_entry" placeholder="※半角英数字で入力してください"
                            value="<?php echo isset($_SESSION['join']['password']) ? htmlspecialchars($_SESSION['join']['password'], ENT_QUOTES) : ''; ?>">
                            <?php $entry->password_error(); ?>
                        </div>

                        <div class="form-group_entry">
                            <label for="last_name">名字</label>
                            <input id="last_name" type="text" name="last_name" class="form-control_entry"
                            value="<?php echo isset($_SESSION['join']['last_name']) ? htmlspecialchars($_SESSION['join']['last_name'], ENT_QUOTES) : ''; ?>">
                            <?php $entry->firstname_error(); ?>
                        </div>

                        <div class="form-group_entry">
                            <label for="first_name">名前</label>
                            <input id="first_name" type="text" name="first_name" class="form-control_entry"
                            value="<?php echo isset($_SESSION['join']['first_name']) ? htmlspecialchars($_SESSION['join']['first_name'], ENT_QUOTES) : ''; ?>">
                            <?php $entry->lastname_error(); ?>
                        </div>

                        <div class="form-group_entry">
                            <label for="birthday">誕生日</label>
                            <input id="birthday" type="date" name="birthday" class="form-control_entry"
                            value="<?php echo isset($_SESSION['join']['birthday']) ? htmlspecialchars($_SESSION['join']['birthday'], ENT_QUOTES) : ''; ?>">
                            <?php $entry->birthday_error(); ?>
                        </div>

                        <!-- DBの負担を減らすためプルダウンは手入力 -->
                        <div class="form-group_entry">
                            <label for="gender_id">性別</label>
                            <select name="gender_id" id="gender_id" class="form-control_entry"
                            value="<?php echo isset($_SESSION['join']['gender_id']) ? htmlspecialchars($_SESSION['join']['gender_id'], ENT_QUOTES) : ''; ?>">
                                <option value="1">女性</option>
                                <option value="2">男性</option>
                                <option value="3">その他</option>
                            </select>
                        </div>

                        <div class="form-group_entry">
                            <label for="role_id">役割</label>
                            <select name="role_id" id="role_id" class="form-control_entry"
                            value="<?php echo isset($_SESSION['join']['role_id']) ? htmlspecialchars($_SESSION['join']['role_id'], ENT_QUOTES) : ''; ?>">
                            <!-- 「FIXME」ログインされていない場合は管理者の役割しか選べないように修正する -->
                            <?php $entry->role_select(); ?>
                            </select>
                        </div>
                        
                        <div class="form-group_entry">
                            <button type="submit" class="btn btn-primary btn_margintop">確認する</button>
                        </div>
                    </div>
                </form>
            </div> 
        </div>
    </div>
</main>

<!-- フッター -->
<?php require_once("../include/footer.php"); ?>