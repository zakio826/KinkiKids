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
            $admin_flags = isset($_POST['admin_flag']) ? $_POST['admin_flag'] : array_fill(0, count($usernames), 0);
            $savings = $_POST['savings'];
            $allowances = $_POST['allowances'];
            $payments = $_POST['payments'];

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
                if (!is_numeric($savings[$i]) || $savings[$i] < 0 || $savings[$i] > 9999999999) {
                    $error['savings'][$i] = "invalid";
                }
                if (!is_numeric($allowances[$i]) || $allowances[$i] < 0 || $allowances[$i] > 9999999999) {
                    $error['allowances'][$i] = "invalid";
                }
                if (!is_numeric($payments[$i]) || $payments[$i] < 0 || $payments[$i] > 31) {
                    $error['payments'][$i] = "invalid";
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
                    $firstlogin = date('Y-m-d');
                    $adminFlag = isset($admin_flags[$i]) ? 1 : 0;

                    $statement = $this->db->prepare(
                        "INSERT INTO user 
                        (username, password, first_name, last_name, birthday, gender_id, role_id, admin_flag, family_id, first_login)
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
                        $adminFlag,
                        $family_id,
                        $firstlogin
                    ));

                    $savedUserId = $this->getUserIdByUsername($usernames[$i]);

                    $allowedRoleIds = [31, 32, 33, 34];
                    if (in_array($role_ids[$i], $allowedRoleIds)) {
                        $allowanceStatement = $this->db->prepare(
                            "INSERT INTO allowance (user_id, family_id, allowance_amount, payment_day)
                            VALUES (?, ?, ?, ?)"
                        );
                        
                        $childStatement = $this->db->prepare(
                            "INSERT INTO child_data (user_id, have_points, max_lending, allowance_id, savings)
                            VALUES (?, ?, ?, ?, ?)"
                        );
                        
                        $allowanceStatement->execute(array(
                            $savedUserId, // 保存されたユーザーのID
                            $family_id,
                            $allowances[$i],
                            $payments[$i]
                        ));
                        
                        $savedAllowanceId = $this->getAllowanceIdByUserId($savedUserId);

                        // 初期値として0をセット（必要に応じて変更）
                        $childStatement->execute(array(
                            $savedUserId, // 保存されたユーザーのID
                            0, // have_points
                            0, // max_lending
                            $savedAllowanceId,
                            $savings[$i]
                        ));
                    }
                }

                unset($_SESSION['join']);   // セッションを破棄
                $_SESSION['family_success'] = true;
                $_SESSION['family_count'] = count($usernames);
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

    private function getUserIdByUsername($username) {
        $stmt = $this->db->prepare("SELECT user_id FROM user WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['user_id'];
    }

    private function getAllowanceIdByUserId($user_id) {
        $stmt = $this->db->prepare("SELECT allowance_id FROM allowance WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['allowance_id'];
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

