document.addEventListener('DOMContentLoaded', function() {
    // ユーザー追加ボタンのクリックイベント
    document.getElementById('addUser').addEventListener('click', function() {
        // 新しいユーザー情報の入力フォームを追加
        var userForm = document.getElementById('userForm');
        var newUserForm = userForm.cloneNode(true);
        var inputs = newUserForm.querySelectorAll('input');
        
        // フォーム内の値をクリア
        inputs.forEach(function(input) {
            if (input.classList.contains('savings-input') || input.classList.contains('allowance-input') || input.classList.contains('payment-input')) {
                input.value = '0';
        } else {
            input.value = '';
        }
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

        toggleSavingsField(newUserForm.querySelector('.roleSelect'));
    });

    // フォーム削除ボタンのクリックイベント（動的に追加された要素にも対応）
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('removeUser')) {
            event.target.parentNode.remove();
        }
    });
});

function toggleSavingsField(roleSelect) {
    var savingsField = roleSelect.parentNode.nextElementSibling;
    

    // 「FIXME」に対応する役割IDの配列
    var allowedRoleIds = [31, 32, 33, 34];

    // 選択された役割IDを取得
    var selectedRoleId = parseInt(roleSelect.value);

    // 選択された役割が許可された役割IDに含まれているか判定
    if (allowedRoleIds.includes(selectedRoleId)) {
        savingsField.style.display = "block";
    } else {
        savingsField.style.display = "none";
    }
}

