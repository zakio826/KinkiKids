<?php

class norma_check {
    private $error; // エラー情報を保持するプロパティ
    private $db; // データベース接続を保持するプロパティ

    function __construct($db) {
        $this->db = $db;
        $this->error = []; // 初期化

        if (!empty($_POST['check'])) {
            $this->saveNormaToDatabase(); 
            header("Location: ../index.php");
            exit();    
        }
    }

    private function saveNormaToDatabase() {

        $existingData = $this->getExistingNormaData($_SESSION['join']['norma_user']);

        if ($existingData) {
            $this->deleteExistingNormaData($_SESSION['join']['norma_user']);
        }

        $statement = $this->db->prepare(
            "INSERT INTO point_norma (user_id, family_id, point_norma_user_id, point_norma_amount, point_norma_deadline, point_norma_created_date) ".
            "VALUES (?, ?, ?, ?, ?, ?)"
        );

        $statement->execute(
            array(
                $_SESSION['join']['user_id'],
                $_SESSION['join']['family_id'],
                $_SESSION['join']['norma_user'],
                $_SESSION['join']['norma_amount'],
                $_SESSION['join']['point_norma_deadline'],
                $_SESSION['join']['point_norma_created_date']
            )
        );
    }

    public function getusername() {
        // データベースから選択されたユーザーIDが一致するユーザーを取得するクエリを実行する
        $stmt = $this->db->prepare("SELECT first_name FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION['join']['norma_user']);
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
        $stmt = $this->db->prepare("SELECT * FROM point_norma WHERE point_norma_user_id = :point_norma_user_id");
        $stmt->bindParam(':point_norma_user_id', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 同じuser_idのデータを削除するメソッド
    private function deleteExistingNormaData($userId) {
        $stmt = $this->db->prepare("DELETE FROM point_norma WHERE point_norma_user_id = :point_norma_user_id");
        $stmt->bindParam(':point_norma_user_id', $userId);
        $stmt->execute();
    }
}
?>