<?php
//短縮やよく使う関数等のPHP

//XSS対策
function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

//セッションにトークンセット
function setToken() {
    $token = sha1(uniqid(mt_rand(), true));
    $_SESSION['token'] = $token;
}

//セッション変数のトークンとPOSTされたトークンをチェック
function checkToken() {
    if(empty($_SESSION['token']) || ($_SESSION['token'] != $_POST['token'])){
        echo 'Invalid POST', PHP_EOL; exit;
    }
}

function sql_check($stmt, $db) {
    //SQLが正しくない場合はエラーを表示
    if (!$stmt) { die($db->error); }
    
    //正しければSQL実行
    $success = $stmt->execute();
    
    //実行されなかったらエラー表示
    if (!$success) { die($db->error); }
  }
  
//POSTされた値のバリデーション
function validation($datas,$confirm = true) {
    $errors = [];

    //ユーザー名のチェック
    if (empty($datas['username'])) {
        $errors['username'] = 'ユーザー名が入力されていません。';
    }
    // else if(mb_strlen($datas['name']) > 20) {
    //     $errors['name'] = 'Please enter up to 20 characters.';
    // }

    //パスワードのチェック（正規表現）
    if (empty($datas["password"])) {
        $errors['password']  = "パスワードが入力されていません。";
    } else if (!preg_match('/\A[a-z\d]{8,100}+\z/i',$datas["password"])) {
        $errors['password'] = "パスワードは8文字以上で入力して下さい。";
    }

    //パスワード入力確認チェック（ユーザー新規登録時のみ使用）
    if ($confirm) {
        if (empty($datas["confirm_password"])) {
            $errors['confirm_password']  = "パスワードを確認してください。";
        } else if (empty($errors['password']) && ($datas["password"] != $datas["confirm_password"])) {
            $errors['confirm_password'] = "パスワードが間違っています。";
        }
    }

    return $errors;
}
?>