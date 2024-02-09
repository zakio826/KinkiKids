<?php

// テスト
class setting_behavioral {
    private $error; // エラー情報を保持するプロパティ
    private $db; // データベース接続を保持するプロパティ

    function __construct($db) {
        $this->db = $db;
        $this->error = []; // 初期化

        $user_id = $_SESSION["user_id"];

        $family_id = $this->getFamilyId($user_id);
        $_SESSION['join']['family_id'] = $family_id;

  


        if (!empty($_POST)) {
            /* 入力情報に空白がないか検知 */
            if (empty($_POST['behavioral_goal'])) {
                $this->error['behavioral_goal'] = 'blank';
            }
            if (empty($_POST['reward_point'])) {
                $this->error['reward_point'] = 'blank';
            }
            if (empty($_POST['behavioral_deadline'])) {
                $this->error['behavioral_deadline'] = "blank";
            }


            
            // エラーがなければ次のページへ
            if (empty($this->error)) {
                $_SESSION['join'] = $_POST;

                $behavioral_created_date = date("Y-m-d H:i:s");

                $_SESSION['join']['user_id'] = $user_id;
                $_SESSION['join']['behavioral_created_date'] = $behavioral_created_date;
                header('Location: ./behavioral_check.php');
                exit();
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
    public function behavioral_error() {
        if (!empty($this->error['behavioral_goal'])) {
            switch ($this->error['behavioral_goal']) {
                //行動目標が入力されてなければエラーを表示
                case 'blank':
                    echo '*行動目標を入力してください。';
                    break;
            }
        }
    }

    public function reward_error() {
        if (!empty($this->error['reward_point'])) {
            switch ($this->error['reward_point']) {
                //報酬が入力されてなければエラーを表示
                case 'blank':
                    echo '*報酬ポイントを入力してください。';
                    break;
            }
        }
    }

    public function deadline_error() {
        if (!empty($this->error['behavioral_deadline'])) {
            switch ($this->error['behavioral_deadline']) {
                //期限が入力されてなければエラーを表示
                case 'blank':
                    echo '*期限を入力してください。';
                    break;
            }
        }
    }

    public function getFamilyUsers($familyId) {
        // データベースから家族IDが一致するユーザーを取得するクエリを実行する
        $stmt = $this->db->prepare("SELECT user_id ,first_name FROM user WHERE family_id = :family_id");
        $stmt->bindParam(':family_id', $familyId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }


}
?>

