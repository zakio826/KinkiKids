<?php
// test
class family_add {
    private $error; // エラー情報を保持するプロパティ
    private $db; // データベース接続を保持するプロパティ

    function __construct($db) {
        $this->db = $db;
        $this->error = []; // 初期化

        if (!empty($_POST)) {
            // フォームから送信された各種情報を取得
            $usernames = $_POST['username'];
            $passwords = $_POST['password'];
            $first_names = $_POST['first_name'];
            $last_names = $_POST['last_name'];
            $birthdays = $_POST['birthday'];
            $gender_ids = $_POST['gender_id'];
            $role_ids = $_POST['role_id'];
            $admin_flags = isset($_POST['admin_flag']) ? $_POST['admin_flag'] : array();
            $savings = $_POST['savings'];
            $family_names = $_POST['family_name'];

            $family_id = $this->getFamilyId($_SESSION["user_id"]);

            for ($i = 0; $i < count($usernames); $i++) {

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
                if ($family_names[$i] === "") {
                    $error['family_name'][$i] = "blank";
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

                for ($i = 0; $i < count($usernames); $i++) {

                    $hash = password_hash($passwords[$i], PASSWORD_BCRYPT);

                    $statement = $this->db->prepare("INSERT INTO family SET family_name=?");
                    $statement->execute(array($family_names[$i]));

                    $statement = $this->db->prepare('SELECT * FROM family WHERE family_name=?');
                    $statement->execute(array($family_names[$i]));
                    $record = $statement->fetch(PDO::FETCH_ASSOC);

                    if ($record !== false) {
                        end($record);
                        $family_id = $record['family_id'];
                    } else {
                        $family_id = null;
                        error_log('Fetch failed in family_add.php');
                    }

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

                unset($_SESSION['join']);
                header('Location: ./thank.php');
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

