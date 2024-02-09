<?php
// テスト
class entry {
    private $error; // エラー情報を保持するプロパティ
    private $db; // データベース接続を保持するプロパティ

    function __construct($db) {
        $this->db = $db;
        $this->error = []; // 初期化

        if (!empty($_POST)) {
            //ユーザー名が半角英数字で入力さているか判定
            if(!preg_match('/\A[a-z\d]{1,100}+\z/i',$_POST['username'])){
                $this->error['username'] = 'format_error';

            }
            //パスワードが半角英数字８文字以上で入力さているか判定
            if(!preg_match('/\A[a-z\d]{8,100}+\z/i',$_POST['password'])){
                $this->error['password'] = 'char_limit';

            }
            /* 入力情報に空白がないか検知 */

            if (empty($_POST['username'])) {
                $this->error['username'] = 'blank';
            }
            if (empty($_POST['password'])) {
                $this->error['password'] = "blank";
            }
            if (empty($_POST['first_name'])) {
                $this->error['first_name'] = 'blank';
            }
            if (empty($_POST['last_name'])) {
                $this->error['last_name'] = 'blank';
            }
            if (empty($_POST['birthday'])) {
                $this->error['birthday'] = 'blank';
            }
            if (empty($_POST['gender_id'])) {
                $this->error['gender_id'] = 'blank';
            }
            if (empty($_POST['role_id'])) {
                $this->error['role_id'] = 'blank';
            }
            if (empty($_POST['family_name'])) {
                $this->error['family_name'] = 'blank';
            }



            /* usernameの重複を検知 */
            if (empty($this->error['username'])) {
                $user = $this->db->prepare('SELECT COUNT(*) as cnt FROM user WHERE username=?');
                $user->execute(array($_POST['username']));
                $record = $user->fetch();
                if ($record['cnt'] > 0) {
                    $this->error['username'] = 'duplicate';
                }
            }

            /* エラーがなければ次のページへ */
            if (empty($this->error)) {
                $_SESSION['join'] = $_POST; // フォームの内容をセッションで保存
                header('Location: ./check.php'); // check.phpへ移動
                exit();
            }
        }
    }

    public function username_error() {
        if (!empty($this->error['username'])) {
            switch ($this->error['username']) {
                //ユーザー名が入力されてなければエラーを表示
                case 'blank':
                    echo '＊ユーザー名を入力してください。';
                    break;
                //ユーザー名が重複していたらエラーを表示
                case 'duplicate':
                    echo '*そのユーザー名は既に使われています。';
                    break;
                //ユーザー名が半角英数字でなければエラーを表示
                case 'format_error':
                    echo '*半角英数字で入力してください。';
                    break;
            }
        }
    }

    public function password_error() {
        if (!empty($this->error['password'])) {
            switch ($this->error['password']) {
                //パスワードが入力されてなければエラーを表示
                case 'blank':
                    echo '*パスワードを入力してください。';
                    break;
                //パスワードが半角英数字８文字以上でなければエラーを表示
                case 'char_limit':
                    echo '*パスワードは半角英数字８文字以上で入力してください。';
                    break;
            }
        }
    }

    public function firstname_error() {
        //苗字が入力されてなければエラーを表示
        if (!empty($this->error['first_name'])) {
            switch ($this->error['first_name']) {
                case 'blank':
                    echo '＊苗字を入力してください。';
                    break;
            }
        }
    }

    public function lastname_error() {
        //名前が入力されてなければエラーを表示
        if (!empty($this->error['last_name'])) {
            switch ($this->error['last_name']) {
                case 'blank':
                    echo '＊名前を入力してください。';
                    break;
            }
        }
    }

    public function birthday_error() {
        //誕生日が入力されてなければエラーを表示
        if (!empty($this->error['birthday'])) {
            switch ($this->error['birthday']) {
                case 'blank':
                    echo '＊誕生日を入力してください。';
                    break;
            }
        }
    }

    public function familyname_error() {
        //家族名が入力されてなければエラーを表示
        if (!empty($this->error['family_name'])) {
            switch ($this->error['family_name']) {
                case 'blank':
                    echo '＊家族名を入力してください。';
                    break;
            }
        }
    }


    public function role_select() {
        // $this->db が null でないことを確認
        if ($this->db !== null) {
            $stmt = $this->db->query("SELECT role_id,role_name FROM role");
            foreach ($stmt as $record) {
                echo '<option value="', $record[0], '">', $record[1], "</option>";
            }
        }
    }
}
?>