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
            <p>当サービスをご利用するために、次のフォームに必要事項をご記入ください。</p>
            <br>
 
            <div id="userFormsContainer">
                <div id="userForm" class="control">
                    <!-- ユーザー情報の入力フォーム -->
                    <div class="control">
                        <label for="username">ユーザー名</label>
                        <input type="text" name="username[]">
                    </div>
                    
                    <div class="control">
                        <label for="password">パスワード</label>
                        <input type="password" name="password[]">
                    </div>

                    <div class="control">
                        <label for="last_name">名字</label>
                        <input type="text" name="last_name[]">
                    </div>

                    <div class="control">
                        <label for="first_name">名前</label>
                        <input type="text" name="first_name[]">
                    </div>

                    <div class="control">
                        <label for="birthday">誕生日</label>
                        <input type="date" name="birthday[]">
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
                        <select name="role_id[]">
                            <?php $family_add->role_select(); ?>
                        </select>
                    </div>

                    <div class="control">
                        <label for="admin_flag">管理者</label>
                        <input type="checkbox" name="admin_flag[]" value="1">
                    </div>

                    <!-- 削除ボタン -->
                    <div class="control">
                        <button type="button" id="removeUser" style="display:none;">マイナス</button>
                    </div>
                </div>
            </div>

            <button type="button" id="addUser">プラス</button>

            <div class="control">
                <button type="submit" class="btn">確認する</button>
            </div>
        </form>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ユーザー追加ボタンのクリックイベント
        document.getElementById('addUser').addEventListener('click', function() {
            // 新しいユーザー情報の入力フォームを追加
            var userForm = document.getElementById('userForm');
            var newUserForm = userForm.cloneNode(true);
            var inputs = newUserForm.querySelectorAll('input');
            
            // フォーム内の値をクリア
            inputs.forEach(function(input) {
                input.value = '';
                if (input.type === 'checkbox') {
                    input.checked = false;
                }
            });

            // 削除ボタンを追加
            var removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'removeUser';
            removeButton.textContent = 'マイナス';
            newUserForm.appendChild(removeButton);

            // ユーザーフォームをコンテナに追加
            document.getElementById('userFormsContainer').appendChild(newUserForm);
        });

        // フォーム削除ボタンのクリックイベント（動的に追加された要素にも対応）
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('removeUser')) {
                event.target.parentNode.remove();
            }
        });
    });
</script>

<!-- フッター -->
<?php require_once("../include/footer.php"); ?>