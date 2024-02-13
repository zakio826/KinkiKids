<?php
function checkUser($db, $joinData) {
 
    /* 会員登録の手続き以外のアクセスを飛ばす */
    if (!isset($_SESSION['join'])) {
        header('Location: ./entry.php'); exit();
    }

    if (!empty($_POST['check'])) {
        // パスワードを暗号化
        $hash = password_hash($joinData['password'], PASSWORD_BCRYPT);
        $admin_flag = True;

        // 家族を挿入
        $statement = $db->prepare("INSERT INTO family SET family_name=?");
        $statement->execute(array($joinData['family_name']));

        // 最新のfamily_idを取得
        $statement = $db->prepare('SELECT family_id FROM family WHERE family_name=? ORDER BY family_id DESC LIMIT 1');
        $statement->execute(array($joinData['family_name']));
        $record = $statement->fetch(PDO::FETCH_ASSOC);

        if ($record !== false) {
            $family_id = $record['family_id'];

            // userテーブルに家族を挿入
            $statement = $db->prepare("INSERT INTO user SET family_id=?");
            $statement->execute(array($family_id));
        } else {
            error_log('Failed to get family_id in check.php');
        }
        

        $statement = $db->prepare(
            "INSERT INTO user 
            (username, password, first_name, last_name, birthday, gender_id, role_id, family_id, admin_flag)
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
            $family_id,
            $admin_flag
        ));

        unset($_SESSION['join']);  // セッションを破棄
        header('Location: ./thank.php'); exit(); // thank.phpへ移動
    }
}
?>