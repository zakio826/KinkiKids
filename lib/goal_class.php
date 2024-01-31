<?php
// test
class goal{
    private $error; // エラー情報を保持するプロパティ
    private $db; // データベース接続を保持するプロパティ

    function __construct($db){
        $this->db = $db;
        $this->error = []; // 初期化

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

                $family_id = $this->getFamilyId($user_id);

                $goal_created_date = date("Y-m-d H:i:s");

                $_SESSION['join']['user_id'] = $user_id;
                $_SESSION['join']['family_id'] = $family_id;
                $_SESSION['join']['goal_created_date'] = $goal_created_date;

                $this->saveGoalToDatabase();

                header('Location: goal_check.php');
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
        $sql = "INSERT INTO goal (user_id, family_id, target_amount, goal_detail, goal_deadline, goal_created_date) VALUES (:user_id, :family_id, :target_amount, :goal_detail, :goal_deadline, :goal_created_date)";
    
        $params = array(
            ':user_id' => $_SESSION['join']['user_id'],
            ':family_id' => $_SESSION['join']['family_id'],
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
        $stmt = $this->db->prepare("SELECT * FROM goal WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}

?>

