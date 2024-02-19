<?php
// test
class family_add {
    private $error; // エラー情報を保持するプロパティ
    private $db; // データベース接続を保持するプロパティ

    function __construct($db) {
        $this->db = $db;
        $this->error = [];

        if (!empty($_POST)) {
            $savedData = isset($_SESSION['join']) ? $_SESSION['join'] : [];
            $savedData['username'] = $_POST['username'];
            $savedData['password'] = $_POST['password'];
            $savedData['first_name'] = $_POST['first_name'];
            $savedData['last_name'] = $_POST['last_name'];
            $savedData['birthday'] = $_POST['birthday'];
            $savedData['gender_id'] = $_POST['gender_id'];
            $savedData['role_id'] = $_POST['role_id'];
            $savedData['admin_flag'] = isset($_POST['admin_flag']) ? $_POST['admin_flag'] : array_fill(0, count($_POST['username']), 0);
            $savedData['savings'] = $_POST['savings'];
            $savedData['allowances'] = $_POST['allowances'];
            $savedData['payments'] = $_POST['payments'];

            $_SESSION['join'] = $savedData;

            // 登録する家族のfamily_idを取得
            $family_id = $this->getFamilyId($_SESSION["user_id"]);

            // フォームから送信された各ユーザー情報をループ処理
            for ($i = 0; $i < count($savedData['username']); $i++) {

                // 入力情報に空白がないか検知
                if ($savedData['username'][$i] === "") {
                    $error['username'][$i] = "blank";
                }
                if ($savedData['password'][$i] === "") {
                    $error['password'][$i] = "blank";
                }
                if(!preg_match('/\A[a-zA-Z0-9._-]{1,20}\z/', $savedData['username'][$i])){
                    $error['username'][$i] = 'format_error';
                }
    
                //パスワードが半角英数字８文字以上で入力さているか判定
                if(!preg_match('/\A[a-z\d]{8,100}+\z/i',$savedData['password'][$i])){
                    $error['password'][$i] = 'char_limit';
                }
                if ($savedData['first_name'][$i] === "") {
                    $error['first_name'][$i] = "blank";
                }
                if ($savedData['last_name'][$i] === "") {
                    $error['last_name'][$i] = "blank";
                }
                if ($savedData['birthday'][$i] === "") {
                    $error['birthday'][$i] = "blank";
                }
                if ($savedData['gender_id'][$i] === "") {
                    $error['gender_id'][$i] = "blank";
                }
                if ($savedData['role_id'][$i] === "") {
                    $error['role_id'][$i] = "blank";
                }
                if (!is_numeric($savedData['savings'][$i]) || $savedData['savings'][$i] < 0 || $savedData['savings'][$i] > 9999999999) {
                    $error['savings'][$i] = "invalid";
                }
                if (!is_numeric($savedData['allowances'][$i]) || $savedData['allowances'][$i] < 0 || $savedData['allowances'][$i] > 9999999999) {
                    $error['allowances'][$i] = "invalid";
                }
                if (!is_numeric($savedData['payments'][$i]) || $savedData['payments'][$i] < 0 || $savedData['payments'][$i] > 31) {
                    $error['payments'][$i] = "invalid";
                }

                // usernameの重複を検知
                $user = $this->db->prepare('SELECT COUNT(*) as cnt FROM user WHERE username=?');
                $user->execute(array($savedData['username'][$i]));
                $record = $user->fetch();

                if ($record['cnt'] > 0) {
                    $this->error['username'][$i] = 'duplicate';
                }  
            }
            
            // エラーがなければ次のページへ
            if (empty($error)) {
                $_SESSION['join'] = $_POST;

                // フォームから送信された各ユーザー情報をループ処理
                for ($i = 0; $i < count($savedData['username']); $i++) {
                    $hash = password_hash($savedData['password'][$i], PASSWORD_BCRYPT);
                    $adminFlag = isset($savedData['admin_flag'][$i]) ? 1 : 0;

                    $statement = $this->db->prepare(
                        "INSERT INTO user (username, password, first_name, last_name, birthday, gender_id, role_id, admin_flag, family_id) ".
                        "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
                    );

                    $statement->execute(array(
                        $savedData['username'][$i],
                        $hash,
                        $savedData['first_name'][$i],
                        $savedData['last_name'][$i],
                        $savedData['birthday'][$i],
                        $savedData['gender_id'][$i],
                        $savedData['role_id'][$i],
                        $adminFlag,
                        $family_id
                    ));

                    $savedUserId = $this->getUserIdByUsername($savedData['username'][$i]);

                    $allowedRoleIds = [31, 32, 33, 34];
                    if (in_array($savedData['role_id'][$i], $allowedRoleIds)) {
                        $allowanceStatement = $this->db->prepare(
                            "INSERT INTO allowance (user_id, family_id, allowance_amount, payment_day) ".
                            "VALUES (?, ?, ?, ?)"
                        );
                        
                        $childStatement = $this->db->prepare(
                            "INSERT INTO child_data (user_id, have_points, allowance_id, savings) ".
                            "VALUES (?, ?, ?, ?)"
                        );
                        
                        $allowanceStatement->execute(array(
                            $savedUserId, // 保存されたユーザーのID
                            $family_id,
                            $savedData['allowances'][$i],
                            $savedData['payments'][$i]
                        ));
                        
                        $savedAllowanceId = $this->getAllowanceIdByUserId($savedUserId);

                        // 初期値として0をセット（必要に応じて変更）
                        $childStatement->execute(array(
                            $savedUserId, // 保存されたユーザーのID
                            0, // have_points
                            $savedAllowanceId,
                            $savedData['savings'][$i]
                        ));
                    }
                }

                unset($_SESSION['join']);   // セッションを破棄
                $_SESSION['family_success'] = true;
                $_SESSION['family_count'] = count($savedData['username']);
                header('Location: ../index.php'); exit(); // thank.phpへ移動
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
                echo '<option value="' . $record[0] . '">' . $record[1] . "</option>";
            }
        }
    }



}
?>