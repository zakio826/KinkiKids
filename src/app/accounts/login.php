<?php
//ファイルの読み込み
require_once("../../../config/db_connect.php");
require_once("../../../lib/functions.php");
//セッション開始
session_start();

$db = new connect();
$pdo = $db;

// セッション変数 $_SESSION["loggedin"]を確認。ログイン済だったらウェルカムページへリダイレクト
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("Location: ./welcome.php");
    exit;
}

// データベース接続を行う
$db = new connect();

//POSTされてきたデータを格納する変数の定義と初期化
$datas = [
    'username'  => '',
    'password'  => '',
    'confirm_password'  => ''
];
$login_err = "";

//GET通信だった場合はセッション変数にトークンを追加
if($_SERVER['REQUEST_METHOD'] != 'POST'){
    setToken();
}

//POST通信だった場合はログイン処理を開始
if($_SERVER["REQUEST_METHOD"] == "POST"){
    ////CSRF対策
    checkToken();

    // POSTされてきたデータを変数に格納
    foreach($datas as $key => $value) {
        if($value = filter_input(INPUT_POST, $key, FILTER_DEFAULT)) {
            $datas[$key] = $value;
        }
    }

    // バリデーション
    $errors = validation($datas,false);
    if(empty($errors)){
        //ユーザーネームから該当するユーザー情報を取得
        $sql = "SELECT user_id,username,password,role_id,admin_flag FROM user WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        // $stmt->bindValue('username',$datas['username'],PDO::PARAM_INT);
        $stmt->bindValue('username',$datas['username'],PDO::PARAM_STR);
        $stmt->execute();

        //ユーザー情報があれば変数に格納
        if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            //パスワードがあっているか確認
            if (password_verify($datas['password'],$row['password'])) {
                //セッションIDをふりなおす
                session_regenerate_id(true);
                //セッション変数にログイン情報を格納
                $_SESSION["loggedin"] = true;
                $_SESSION["user_id"] = $row['user_id'];
                $_SESSION["username"] =  $row['username'];
                $_SESSION["role_id"] =  $row['role_id'];
                $_SESSION["admin_flag"] =  $row['admin_flag'];
                if (floor($row['role_id'] / 10 ) == 2){
                    $_SESSION["select"] = 'adult';
                }else{
                    $_SESSION["select"] = 'child';
                }
                
                //ウェルカムページへリダイレクト
                header("location:welcome.php");
                //初回ログイン時の処理
                if (empty($row['first_login'])) {
                    $sql = "UPDATE user SET first_login = 1 WHERE user_id = " . $row['user_id'];
                    $stmt = $db->prepare($sql);
                    $stmt->execute();
                    //ウェルカムページへリダイレクト
                    header("Location: ./welcome.php");
                } else {
                    //ホームページへリダイレクト
                    header("Location: ./welcome.php");
                    // header("Location: ../chat/testpoint.php");
                }
                exit();
            } else {
                $login_err = 'Invalid username or password.';
            }
        }else {
            $login_err = 'Invalid username or password.';
        }
    }
}
?>

<?php
$page_title = "ログイン";
require_once("../include/header.php");
?>

<main>
    <div class="wrapper">
        <h2>ログイン</h2>
        <p>ユーザー名、パスワードを入力しログインしてください</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo $_SERVER['SCRIPT_NAME'];; ?>" method="post">
            <div class="form-group">
                <label>ユーザー名</label>
                <input type="text" name="username" class="form-control <?php echo (!empty(h($errors['username']))) ? 'is-invalid' : ''; ?>" value="<?php echo h($datas['username']); ?>">
                <span class="invalid-feedback"><?php echo h($errors['username']); ?></span>
            </div>    
            <div class="form-group">
                <label>パスワード</label>
                <input type="password" name="password" class="form-control <?php echo (!empty(h($errors['password']))) ? 'is-invalid' : ''; ?>" value="<?php echo h($datas['password']); ?>">
                <span class="invalid-feedback"><?php echo h($errors['password']); ?></span>
            </div>
            <div class="form-group">
                <input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>アカウントをお持ちでない方 <a href="./entry.php">サインアップ</a></p>
        </form>
    </div>
</main>

<?php require_once("../include/footer.php"); ?>