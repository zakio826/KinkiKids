<?php
date_default_timezone_set('Asia/Tokyo');

class consent {
    private $db;
    private $error;

    function __construct($db) {
        $this->db = $db;
        $this->error = []; // 初期化

            if (isset($_POST["consent_help_id"])){//ポイント追加処理
                $help_id = $_POST["consent_help_id"];

                $stmt = $this->db->prepare("UPDATE help_log SET receive_flag = 1,consent_flag = 0 WHERE help_id = :help_id and consent_flag = 1");
                $stmt->bindParam(':help_id', $help_id);
                $stmt->execute();
                $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo "承認しました";

                // foreach ($result as $row) {
                //     $stmt2 = $this->db->prepare("UPDATE child_data SET have_points = have_points + :get_point WHERE user_id = :user_id");
                //     $stmt2->bindParam(':get_point', $get_point);
                //     $stmt2->bindParam(':user_id', $row['user_id']);
                //     $stmt2->execute();
                //     $stmt2->fetchAll(PDO::FETCH_ASSOC);
                // }

                // $stmt = $this->db->prepare("UPDATE help_log SET consent_flag = 0 WHERE consent_flag = 1 and help_id = :help_id");
                // $stmt->bindParam(':help_id', $help_id);
                // $stmt->execute();
                // $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                
            }
        }
        public function display_consent_help($user_id) {
            $stmt = $this->db->prepare("SELECT help.help_name,help.get_point,help.help_id FROM help
                                        INNER JOIN help_log ON help.help_id = help_log.help_id 
                                        WHERE help_log.consent_flag = 1 and help.user_id = :user_id and help.stop_flag = 1");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

    public function person_select($help_id) {
        $stmt = $this->db->prepare(
            "SELECT user.first_name FROM user ".
            "INNER JOIN help_person ON user.user_id = help_person.user_id ".
            "WHERE help_id = :help_id"
        );
        $stmt->bindParam(':help_id', $help_id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $first_flag = 0;
        foreach ($result as $row) {
            if ($first_flag != 0) { echo ","; }
            $first_flag = 1;
            echo $row['first_name'];
        }
    }
}
?>