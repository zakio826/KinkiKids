<?php
date_default_timezone_set('Asia/Tokyo');

class consent {
    private $db;
    private $error;

    function __construct($db) {
        $this->db = $db;
        $this->error = []; // 初期化

            if (isset($_POST["consent_help_id"])){
                $help_id = $_POST["consent_help_id"];

                $stmt = $this->db->prepare("UPDATE help_log SET receive_flag = 1,consent_flag = 0 WHERE help_id = :help_id and consent_flag = 1");
                $stmt->bindParam(':help_id', $help_id);
                $stmt->execute();
                $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo "承認しました";
                
            }elseif (isset($_POST["consent_mission_id"])){
                $mission_id = $_POST["consent_mission_id"];

                $stmt = $this->db->prepare("UPDATE mission_log SET receive_flag = 1,consent_flag = 0 WHERE mission_id = :mission_id and consent_flag = 1");
                $stmt->bindParam(':mission_id', $mission_id);
                $stmt->execute();
                $stmt->fetchAll(PDO::FETCH_ASSOC);

                //拒否の場合はdisplayflag戻して
                $stmt2 = $this->db->prepare("UPDATE mission SET display_flag = 0 WHERE mission_id = :mission_id");
                $stmt2->bindParam(':mission_id', $mission_id);
                $stmt2->execute();
                $stmt2->fetchAll(PDO::FETCH_ASSOC);

                echo "承認しました";
                
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

        public function display_consent_mission($user_id) {
            $stmt = $this->db->prepare("SELECT mission.mission_name,mission.get_point,mission.mission_id FROM mission
                                        INNER JOIN mission_log ON mission.mission_id = mission_log.mission_id 
                                        WHERE mission_log.consent_flag = 1 and mission.user_id = :user_id and mission.display_flag = 1");
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

    public function m_person_select($mission_id) {
        $stmt = $this->db->prepare(
            "SELECT user.first_name FROM user ".
            "INNER JOIN mission_person ON user.user_id = mission_person.user_id ".
            "WHERE mission_id = :mission_id"
        );
        $stmt->bindParam(':mission_id', $mission_id);
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