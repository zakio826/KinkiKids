<!-- ユーザー登録ページ -->

<!-- ヘッダー -->
<?php
$page_title = "アカウント作成";
require_once("../include/header.php");
?>

<?php 
require("../../../lib/entry_class.php");
// entryクラスのインスタンスを作成
$entry = new entry($db);

// フォームが送信されたかどうかを確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entry->__construct($db);
}
?>

<main>
    <div class="content">
        <form action="entry.php" method="POST">
            <h1>アカウント作成</h1>
            <p>当サービスをご利用するために、次のフォームに必要事項をご記入ください。</p>
            <br>
 
            <div class="control">
                <label for="username">ユーザー名</label>
                <input id="username" type="text" name="username">
                <?php $entry->username_error(); ?>
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input id="password" type="password" name="password" class="form-control">
                <?php $entry->password_error(); ?>
            </div>

            <div class="control">
                <label for="family_name">家族名</label>
                <input id="family_name" type="text" name="family_name">
                <?php $entry->familyname_error(); ?>
            </div>

            <div class="control">
                <label for="last_name">名字</label>
                <input id="last_name" type="text" name="last_name">
                <?php $entry->firstname_error(); ?>
            </div>

            <div class="control">
                <label for="first_name">名前</label>
                <input id="first_name" type="text" name="first_name">
                <?php $entry->lastname_error(); ?>
            </div>

            <div class="control">
                <label for="birthday">誕生日</label>
                <input id="birthday" type="date" name="birthday">
                <?php $entry->birthday_error(); ?>
            </div>

                <!-- DBの負担を減らすためプルダウンは手入力 -->
                <div class="form-group">
                    <label for="gender_id">性別</label>
                    <select name="gender_id" id="gender_id" class="form-control">
                        <option value="1">女性</option>
                        <option value="2">男性</option>
                        <option value="3">その他</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="role_id">役割</label>
                    <select name="role_id" id="role_id" class="form-control">
                    <!-- 「FIXME」ログインされていない場合は管理者の役割しか選べないように修正する -->
                    <?php $entry->role_select(); ?>
                    </select>
                </div>
            
            <!-- 「FIXME」管理ユーザーがログインしている場合は表示しないようにする -->
            
 
            <div class="control">
                <button type="submit" class="btn">確認する</button>
            </div>
        </form>
    </div>
</main>

<!-- フッター -->
<?php require_once("../include/footer.php"); ?>