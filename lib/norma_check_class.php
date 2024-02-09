<?php

class norma_check {
    private $error; // エラー情報を保持するプロパティ
    private $db; // データベース接続を保持するプロパティ

    function __construct($db) {
        $this->db = $db;
        $this->error = []; // 初期化

        if (!empty($_POST['check'])) {
            $this->saveNormaToDatabase(); exit();    
        }
    }

    private function saveNormaToDatabase() {
        $statement = $this->db->prepare(
            "INSERT INTO point_norma (user_id, family_id, point_norma_amount, point_norma_deadline, point_norma_created_date) ".
            "VALUES (?, ?, ?, ?, ?)"
        );

        $statement->execute(
            array(
                $_SESSION['join']['user_id'],
                $_SESSION['join']['family_id'],
                $_SESSION['join']['norma_amount'],
                $_SESSION['join']['point_norma_deadline'],
                $_SESSION['join']['point_norma_created_date']
            )
        );
    }
}
?>