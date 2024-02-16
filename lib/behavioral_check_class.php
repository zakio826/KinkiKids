<?php

class behavioral_check{
    private $error; // エラー情報を保持するプロパティ
    private $db; // データベース接続を保持するプロパティ

    function __construct($db){
        $this->db = $db;
        $this->error = []; // 初期化

        if (!empty($_POST['check'])) {
            $_SESSION['join']['family_id'] = $this->getFamilyId($_SESSION['join']['user_id']);
            $this->saveBehavioralToDatabase();
            header("Location: ../index.php");
            exit();    
        }

    }

    private function saveBehavioralToDatabase() {

        $existingData = $this->getExistingNormaData($_SESSION['join']['behavioral_user']);

        if ($existingData) {
            $this->deleteExistingNormaData($_SESSION['join']['behavioral_user']);
        }
        $statement = $this->db->prepare(
            "INSERT INTO behavioral_goal 
            (user_id, family_id, behavioral_goal_user_id, behavioral_goal, reward_point,approval_flag, behavioral_goal_deadline, point_norma_created_date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $statement->execute(array(
            $_SESSION['join']['user_id'],
            $_SESSION['join']['family_id'],
            $_SESSION['join']['behavioral_user'],
            $_SESSION['join']['behavioral_goal'],
            $_SESSION['join']['reward_point'],
            0,
            $_SESSION['join']['behavioral_deadline'],
            $_SESSION['join']['behavioral_created_date']
        ));
    }

    public function getusername() {
        // データベースから選択されたユーザーIDが一致するユーザーを取得するクエリを実行する
        $stmt = $this->db->prepare("SELECT first_name FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION['join']['behavioral_user']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['first_name'];
    }

    //ユーザーのfamily_idを取得する関数
    private function getFamilyId($user_id){
        $stmt = $this->db->prepare("SELECT family_id FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['family_id'];
    }

    
    // 同じuser_idのデータが存在するか確認するメソッド
    private function getExistingNormaData($userId) {
        $stmt = $this->db->prepare("SELECT * FROM behavioral_goal WHERE behavioral_goal_user_id = :behavioral_goal_user_id");
        $stmt->bindParam(':behavioral_goal_user_id', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 同じuser_idのデータを削除するメソッド
    private function deleteExistingNormaData($userId) {
        $stmt = $this->db->prepare("DELETE FROM behavioral_goal WHERE behavioral_goal_user_id = :behavioral_goal_user_id");
        $stmt->bindParam(':behavioral_goal_user_id', $userId);
        $stmt->execute();
    }

}




?>