<?php

// テスト
class setting_norma {
    private $error; // エラー情報を保持するプロパティ
    private $db; // データベース接続を保持するプロパティ

    function __construct($db) {
        $this->db = $db;
        $this->error = []; // 初期化

        $user_id = $_SESSION["user_id"];

        $family_id = $this->getFamilyId($user_id);
        $_SESSION['join']['family_id'] = $family_id;

        if (!empty($_POST)) {

            // 入力情報に空白がないか検知
            if (empty($_POST['norma_amount'])) {
                $this->error['norma_amount'] = 'blank';
            }
            if (empty($_POST['point_norma_deadline'])) {
                $this->error['point_norma_deadline'] = "blank";
            }
            
            // エラーがなければ次のページへ
            if (empty($this->error)) {
                $_SESSION['join'] = $_POST;

                $user_id = $_SESSION["user_id"];

                $family_id = $this->getFamilyId($user_id);

                $point_norma_created_date = date("Y-m-d H:i:s");

                $_SESSION['join']['user_id'] = $user_id;
                $_SESSION['join']['family_id'] = $family_id;
                $_SESSION['join']['point_norma_created_date'] = $point_norma_created_date;

                header('Location: ./norma_check.php'); exit();
            }
        }
    }

    //ユーザーのfamily_idを取得する関数
    private function getFamilyId($user_id){
        $stmt = $this->db->prepare("SELECT family_id FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['family_id'];
    }

    public function norma_error() {
        if (!empty($this->error['norma_amount'])) {
            switch ($this->error['norma_amount']) {
                //ノルマが入力されてなければエラーを表示
                case 'blank': echo '*ノルマを入力してください。'; break;
            }
        }
    }

    public function deadline_error() {
        if (!empty($this->error['point_norma_deadline'])) {
            switch ($this->error['point_norma_deadline']) {
                //期限が入力されてなければエラーを表示
                case 'blank': echo '*期限を入力してください。'; break;
            }
        }
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
}
?>