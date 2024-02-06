<?php

    class help
    {
        private $db;
        private $error;

        function __construct($db){
            $this->db = $db;
            $this->error = []; // 初期化

        if (!empty($_POST)) {
                if (isset($_POST["delete_help_id"])){
                    $this->DeleteHelpToDatabase($_POST["delete_help_id"]);
                }elseif(isset($_POST["consent_help_id"])){
                    $this->consentHelpToDatabase($_POST["consent_help_id"]);
                }else{
                /* 入力情報に空白がないか検知 */
                if ($_POST['help_name'] === "") {
                    $error['help_name'] = "blank";
                }
                if ($_POST['get_point'] === "") {
                    $error['get_point'] = "blank";
                }
                if (isset($_POST['help_person'])){
                    $person = $_POST['help_person'];
                }else{
                    $error['get_person'] = "blank";
                }if (isset($_POST['help_person'])){
                    $person = $_POST['help_person'];
                }else{
                    $error['get_person'] = "blank";
                }
                
                
                //エラーがなければ次のページへ
                if (!isset($error)) {
                    $_SESSION['join'] = $_POST;

                        $user_id = $_SESSION["user_id"];

                        $family_id = $this->getFamilyId($user_id);

                        $_SESSION['join']['user_id'] = $user_id;
                        $_SESSION['join']['family_id'] = $family_id;

                        $this->saveHelpToDatabase();
                    $this->saveHelppersonToDatabase($person);
                        header('Location: ./help_add.php');
                        exit();
                    }
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
            $sql = "INSERT INTO help (user_id, family_id, help_name, help_detail, get_point,stop_flag) VALUES (:user_id, :family_id, :help_name, :help_detail, :get_point,1)";
        
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
    private function saveHelppersonToDatabase($persons) {
        //$help_id = 5;
        $stmt2 = $this->db->prepare("SELECT help_id FROM help ORDER BY help_id DESC LIMIT 1");
        $stmt2->execute();
        $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        $help_id = (int)$result2[0]["help_id"];

        foreach ($persons as $person) {
            echo $person;

            $stmt = $this->db->prepare("INSERT INTO help_person (help_id,user_id) VALUES (:help_id, :user_id)");
            $stmt->bindParam(':help_id', $help_id);
            $stmt->bindParam(':user_id', $person);
            $stmt->execute();
            $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
    }

        public function display_help($family_id) {
            $stmt = $this->db->prepare("SELECT * FROM help WHERE family_id = :family_id");
            $stmt->bindParam(':family_id', $family_id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }

        private function DeleteHelpToDatabase($help_id) {
            $stmt = $this->db->prepare("DELETE FROM help WHERE help_id = :help_id");
            $stmt->bindParam(':help_id', $help_id);
            $stmt->execute();
            $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<p>削除しました</p>";
        }

    private function consentHelpToDatabase($help_id) {
        if(isset($_SESSION["user_id"])){
            $user_id = $_SESSION["user_id"];
            $dtime = date("Y-m-d H:i:s");
            $stmt = $this->db->prepare("INSERT INTO help_log (user_id,help_id,help_day,consent_flag) values(:user_id, :help_id, :dtime, 1)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':help_id', $help_id);
            $stmt->bindParam(':dtime', $dtime);
            $stmt->execute();
            $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<p>承認待ち</p>";//TODO 承認待ちの処理
        }else{
            //TODO ログインしていない
        }
        
    }

    public function child_select() {
        if(isset($_SESSION["family_id"])){
            $stmt = $this->db->prepare("SELECT user_id,first_name,role_id FROM user WHERE family_id = :family_id");
            $stmt->bindParam(':family_id', $_SESSION["family_id"]);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $person){
                if (floor($person['role_id'] / 10 ) == 3){
                    echo "<input type='checkbox' name='help_person[]' value=".$person['user_id'].">";
                    echo $person['first_name']."　";
                }
            }
        }else{
            //TODO ログインしていない
        }
        
    }

        public function getHelpInfo($help_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM help WHERE help_id = :help_id");
        $stmt->bindParam(':help_id', $help_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateHelp($data)
{
    $help_id = $data['help_id'];
    $help_name = $data['help_name'];
    $help_detail = $data['help_detail'];
    $get_point = $data['get_point'];

    $sql = "UPDATE help SET help_name = :help_name, help_detail = :help_detail, get_point = :get_point WHERE help_id = :help_id";

    $params = array(
        ':help_id' => $help_id,
        ':help_name' => $help_name,
        ':help_detail' => $help_detail,
        ':get_point' => $get_point
    );

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
}

    }
?>
