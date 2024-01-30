<?php
// test
class entry{
    private $error; // エラー情報を保持するプロパティ
    private $db; // データベース接続を保持するプロパティ

    function __construct($db){
        $this->db = $db;
        $this->error = []; // 初期化

        if (!empty($_POST)) {
            /* 入力情報に空白がないか検知 */
            if ($_POST['username'] === "") {
                $error['username'] = "blank";
            }
            if ($_POST['password'] === "") {
                $error['password'] = "blank";
            }
            if ($_POST['first_name'] === "") {
                $error['first_name'] = "blank";
            }
            if ($_POST['last_name'] === "") {
                $error['last_name'] = "blank";
            }
            if ($_POST['birthday'] === "") {
                $error['birthday'] = "blank";
            }
            if ($_POST['gender_id'] === "") {
                $error['gender_id'] = "blank";
            }
            if ($_POST['role_id'] === "") {
                $error['role_id'] = "blank";
            }
            if ($_POST['role_id'] === "") {
                $error['role_id'] = "blank";
            }
            if ($_POST['family_name'] === "") {
                $error['family_name'] = "blank";
            }
        
            /* usernameの重複を検知 */
            if (!isset($error)) {
                $user = $db->prepare('SELECT COUNT(*) as cnt FROM user WHERE username=?');
                $user->execute(array(
                    $_POST['username']
                ));
                $record = $user->fetch();
                if ($record['cnt'] > 0) {
                    $error['username'] = 'duplicate';
                }
            }
         
            /* エラーがなければ次のページへ */
            if (!isset($error)) {
                $_SESSION['join'] = $_POST;   // フォームの内容をセッションで保存
                header('Location: check.php');   // check.phpへ移動
                exit();
            }
        }
    }

    public function password_error(){
        if (!empty($this->error["password"]) && $this->error['password'] === 'blank'){
            echo('<p class="error">＊パスワードを入力してください</p>');
        }
    }

    public function role_select(){
        // $this->db が null でないことを確認
        if ($this->db !== null) { 
            $stmt = $this->db->query("SELECT role_id,role_name FROM role");
            foreach($stmt as $record){
                echo '<option value="';
                echo $record[0];
                echo '">';
                echo $record[1];
                echo "</option>";
            }
        }
    }
}

?>