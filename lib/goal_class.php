<?php
// test
class goal {
    private $error; // エラー情報を保持するプロパティ
    private $db; // データベース接続を保持するプロパティ

    function __construct($db) {
        $this->db = $db;
        $this->error = []; // 初期化

        $family_id = $this->getFamilyId($_SESSION['user_id']);
        $_SESSION['join']['family_id'] = $family_id;

        if (!empty($_POST)) {
            /* 入力情報に空白がないか検知 */
            if ($_POST['target_amount'] === "") {
                $error['target_amount'] = "blank";
            }
            if ($_POST['goal_detail'] === "") {
                $error['goal_detail'] = "blank";
            }
            if ($_POST['goal_deadline'] === "") {
                $error['goal_deadline'] = "blank";
            }
            
            // エラーがなければ次のページへ
            if (!isset($error)) {
                $_SESSION['join'] = $_POST;

                $user_id = $_SESSION["user_id"];
                
                $family_id = $this->getFamilyId($_SESSION['user_id']);
                $_SESSION['join']['family_id'] = $family_id;

                $goal_created_date = date("Y-m-d H:i:s");

                $_SESSION['join']['user_id'] = $user_id;
                $_SESSION['join']['goal_created_date'] = $goal_created_date;

                $this->saveGoalToDatabase();

                // $current_date = date("Y-m-d");
                // $query = "DELETE FROM goal WHERE family_id = :family_id AND goal_deadline < :current_date";
                // $stmt = $this->db->prepare($query);
                // $stmt->bindParam(':family_id', $family_id);
                // $stmt->bindParam(':current_date', $current_date);
                // $stmt->execute();

                header('Location: ./goal_check.php'); 
                exit();
            }
        }
    }

    // ユーザーのfamily_idを取得する関数
    private function getFamilyId($user_id){
        $stmt = $this->db->prepare("SELECT family_id FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['family_id'];
    }

    private function saveGoalToDatabase() {
        $sql = "INSERT INTO goal (user_id, family_id, goal_user_id, target_amount, goal_detail, goal_deadline, goal_created_date) ".
        "VALUES (:user_id, :family_id, :goal_user_id, :target_amount, :goal_detail, :goal_deadline, :goal_created_date)";
    
        $params = array(
            ':user_id' => $_SESSION['join']['user_id'],
            ':family_id' => $_SESSION['join']['family_id'],
            ':goal_user_id' => $_SESSION['join']['goal_user'],
            ':target_amount' => $_SESSION['join']['target_amount'],
            ':goal_detail' => $_SESSION['join']['goal_detail'],
            ':goal_deadline' => $_SESSION['join']['goal_deadline'],
            ':goal_created_date' => $_SESSION['join']['goal_created_date']
        );
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }
    

    // ユーザーが登録した目標の情報を取得する関数
    public function getUserGoals($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM goal WHERE goal_user_id = :goal_user_id");
        $stmt->bindParam(':goal_user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function deleteGoal($user_id) {
        // データベースから目標を削除する処理を実装
        $stmt = $this->db->prepare("DELETE FROM goal WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    }

    public function getFamilyUsers($familyId) {
        // データベースから家族IDが一致するユーザーを取得するクエリを実行する
        $stmt = $this->db->prepare("SELECT user_id, first_name FROM user WHERE family_id = :family_id AND role_id NOT IN (21, 22, 23, 24)");
        $stmt->bindParam(':family_id', $familyId);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // 空の配列を用意
        $userIds = [];
        $firstNames = [];

        // 結果をループして取得
        foreach ($result as $row) {
            $userIds[] = $row['user_id'];
            $firstNames[] = $row['first_name'];
        }

        // 配列を返す
        return array($userIds, $firstNames);
        }
    
    public function deleteExpiredGoals($family_id) {
        $current_date = date("Y-m-d");
        $query = "DELETE FROM goal WHERE family_id = :family_id AND goal_deadline < :current_date";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':family_id', $family_id);
        $stmt->bindParam(':current_date', $current_date);
        $stmt->execute();
    }
}

class goal_check{
    private $error; // エラー情報を保持するプロパティ
    private $db; // データベース接続を保持するプロパティ

    public function getusername($db) {
        $this->db = $db;
        $this->error = []; // 初期化
        // データベースから選択されたユーザーIDが一致するユーザーを取得するクエリを実行する
        $stmt = $this->db->prepare("SELECT first_name FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION['join']['goal_user']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['first_name'];
    }

    public function getchildname($db, $user_id) {
        $this->db = $db;
        $this->error = []; // 初期化
        // データベースから選択されたユーザーIDが一致するユーザーを取得するクエリを実行する
        $stmt = $this->db->prepare("SELECT first_name FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['first_name'];
    }
}
?>