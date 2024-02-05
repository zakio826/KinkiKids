<!-- ユーザー登録ページ -->

<!-- ヘッダー -->
<?php
$page_title = "アカウント作成";
require_once("../include/header.php");
?>

<?php
// family_addクラスのインスタンスを作成
require($absolute_path."lib/family_add_class.php");
$family_add = new family_add($db);
?>

<script>
    // ドキュメントが読み込まれたら実行
    document.addEventListener('DOMContentLoaded', function () {
        // ユーザー追加ボタンがクリックされた時の処理を設定
        document.getElementById('addUser').addEventListener('click', function () {
            // 新しいユーザー情報の入力フォームを複製
            var newUserForm = document.getElementById('userForm').cloneNode(true);
            
            // 複製したフォームを追加
            document.getElementById('userFormsContainer').appendChild(newUserForm);
        });
    });
</script>

<main>
    <div class="content">
        <form action="" method="POST">
            <h1>アカウント作成</h1>
            <p>当サービスをご利用するために、次のフォームに必要事項をご記入ください。</p>
            <br>
 
            <div id="userFormsContainer">
                <div id="userForm" class="control">
                    <!-- ユーザー情報の入力フォーム -->
                    <label for="username">ユーザー名</label>
                    <input type="text" name="username[]">
                    
                    <label for="password">パスワード</label>
                    <input type="password" name="password[]">

                    <label for="last_name">名字</label>
                    <input type="text" name="last_name[]">

                    <label for="first_name">名前</label>
                    <input type="text" name="first_name[]">

                    <label for="birthday">誕生日</label>
                    <input type="date" name="birthday[]">

                    <label for="gender_id">性別</label>
                    <select name="gender_id[]">
                        <option value="1">女性</option>
                        <option value="2">男性</option>
                        <option value="3">その他</option>
                    </select>

                    <label for="role_id">役割</label>
                    <select name="role_id[]">
                        <?php $family_add->role_select(); ?>
                    </select>

                    <label for="admin_flag">管理者</label>
                    <input type="checkbox" name="admin_flag[]" value="1">

                    <label for="savings">貯蓄</label>
                    <input type="text" name="savings[]">

                    <label for="family_name">家族名</label>
                    <input type="text" name="family_name[]">
                </div>
            </div>

            <button type="button" id="addUser">プラス</button>

            <div class="control">
                <button type="submit" class="btn">確認する</button>
            </div>
        </form>
    </div>
</main>
</html>
