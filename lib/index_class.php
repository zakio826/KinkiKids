<?php
// test
class index_class {
    private $error; // エラー情報を保持するプロパティ
    private $db; // データベース接続を保持するプロパティ
    
    function __construct($db) {
        $this->db = $db;
        $this->error = []; // 初期化
    }

    public function child_adult() {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $role_id = $result['role_id'];

        return $role_id;
    }
    
}
?>