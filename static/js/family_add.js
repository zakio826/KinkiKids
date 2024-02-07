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
        removeButton.textContent = '－';
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