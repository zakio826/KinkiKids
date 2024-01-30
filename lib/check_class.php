<?php

function checkUser($db, $joinData) {
 
    /* 会員登録の手続き以外のアクセスを飛ばす */
    if (!isset($_SESSION['join'])) {
        header('Location: entry.php');
        exit();
    }

    if (!empty($_POST['check'])) {
        // パスワードを暗号化
        $hash = password_hash($joinData['password'], PASSWORD_BCRYPT);

        // 家族を挿入
        $statement = $db->prepare("INSERT INTO family SET family_name=?");
        $statement->execute(array($joinData['family_name']));

        $statement = $db->prepare('SELECT * FROM family WHERE family_name=?');
        $statement->execute(array($joinData['family_name']));
        $record = $statement->fetch(PDO::FETCH_ASSOC);

        if ($record !== false) {
            end($record);
            $family_id = $record['family_id'];
        } else {
            $family_id = null;
            error_log('Fetch failed in check.php');
        }

        $statement = $db->prepare(
            "INSERT INTO user 
            (username, password, first_name, last_name, birthday, gender_id, role_id, savings, family_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $statement->execute(array(
            $joinData['username'],
            $hash,
            $joinData['first_name'],
            $joinData['last_name'],
            $joinData['birthday'],
            $joinData['gender_id'],
            $joinData['role_id'],
            $joinData['savings'],
            $family_id
        ));
        unset($_SESSION['join']);   // セッションを破棄
        header('Location: thank.php');   // thank.phpへ移動
        exit();    
    }
}

?>