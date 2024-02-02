<?php

class help
{
    private $db;
    private $error;

    function __construct($db){
        $this->db = $db;
        $this->error = []; // 初期化

        if (!empty($_POST)) {
            /* 入力情報に空白がないか検知 */
            if ($_POST['help_name'] === "") {
                $error['help_name'] = "blank";
            }
            if ($_POST['help_detail'] === "") {
                $error['help_detail'] = "blank";
            }
            if ($_POST['get_point'] === "") {
                $error['get_point'] = "blank";
            }
            
            // エラーがなければ次のページへ
            if (!isset($error)) {
                $_SESSION['join'] = $_POST;

                $user_id = $_SESSION["user_id"];

                $family_id = $this->getFamilyId($user_id);

                $_SESSION['join']['user_id'] = $user_id;
                $_SESSION['join']['family_id'] = $family_id;

                $this->saveHelpToDatabase();
                header('Location: ./help_add.php');
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

    private function saveHelpToDatabase() {
        $sql = "INSERT INTO help (user_id, family_id, help_name, help_detail, get_point) VALUES (:user_id, :family_id, :help_name, :help_detail, :get_point)";
    
        $params = array(
            ':user_id' => $_SESSION['join']['user_id'],
            ':family_id' => $_SESSION['join']['family_id'],
            ':help_name' => $_SESSION['join']['help_name'],
            ':help_detail' => $_SESSION['join']['help_detail'],
            ':get_point' => $_SESSION['join']['get_point']
        );
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }

    public function display_help($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM help WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}
?>
