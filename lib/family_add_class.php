<?php
// test
class family_add {
    private $error; // エラー情報を保持するプロパティ
    private $db; // データベース接続を保持するプロパティ

    function __construct($db) {
        $this->db = $db;
        $this->error = [];

        if (!empty($_POST)) {
            
            $usernames = $_POST['username'];
            $passwords = $_POST['password'];
            $first_names = $_POST['first_name'];
            $last_names = $_POST['last_name'];
            $birthdays = $_POST['birthday'];
            $gender_ids = $_POST['gender_id'];
            $role_ids = $_POST['role_id'];
            $admin_flags = isset($_POST['admin_flag']) ? $_POST['admin_flag'] : array();
            $savings = $_POST['savings'];

            // 登録する家族のfamily_idを取得
            $family_id = $this->getFamilyId($_SESSION["user_id"]);

            // フォームから送信された各ユーザー情報をループ処理
            for ($i = 0; $i < count($usernames); $i++) {
                // 入力情報に空白がないか検知
                if ($usernames[$i] === "") {
                    $error['username'][$i] = "blank";
                }
                if ($passwords[$i] === "") {
                    $error['password'][$i] = "blank";
                }
                if ($first_names[$i] === "") {
                    $error['first_name'][$i] = "blank";
                }
                if ($last_names[$i] === "") {
                    $error['last_name'][$i] = "blank";
                }
                if ($birthdays[$i] === "") {
                    $error['birthday'][$i] = "blank";
                }
                if ($gender_ids[$i] === "") {
                    $error['gender_id'][$i] = "blank";
                }
                if ($role_ids[$i] === "") {
                    $error['role_id'][$i] = "blank";
                }
                if ($role_ids[$i] === "") {
                    $error['savings'][$i] = "blank";
                }

                // usernameの重複を検知
                $user = $this->db->prepare('SELECT COUNT(*) as cnt FROM user WHERE username=?');
                $user->execute(array($usernames[$i]));
                $record = $user->fetch();
                if ($record['cnt'] > 0) {
                    $error['username'][$i] = 'duplicate';
                }
            }

            // エラーがなければ次のページへ
            if (!isset($error)) {

                $_SESSION['join'] = $_POST;

                // フォームから送信された各ユーザー情報をループ処理
                for ($i = 0; $i < count($usernames); $i++) {
                    
                    $hash = password_hash($passwords[$i], PASSWORD_BCRYPT);

                    $statement = $this->db->prepare(
                        "INSERT INTO user 
                        (username, password, first_name, last_name, birthday, gender_id, role_id, admin_flag, savings, family_id)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                    );

                    $statement->execute(array(
                        $usernames[$i],
                        $hash,
                        $first_names[$i],
                        $last_names[$i],
                        $birthdays[$i],
                        $gender_ids[$i],
                        $role_ids[$i],
                        isset($admin_flags[$i]) ? $admin_flags[$i] : 0,
                        $savings[$i],
                        $family_id
                    ));
                }

                unset($_SESSION['join']);   // セッションを破棄
                header('Location: ../index.php');   // thank.phpへ移動
                exit();
            }
        }
    }

    // ユーザーのfamily_idを取得する関数
    private function getFamilyId($user_id) {
        $stmt = $this->db->prepare("SELECT family_id FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['family_id'];
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

