<?php
date_default_timezone_set('Asia/Tokyo');

class consent {
    private $db;
    private $error;

    function __construct($db) {
        $this->db = $db;
        $this->error = []; // 初期化

        if (isset($_POST["consent_help_Y"])){
            $help_id = $_POST["consent_help_id"];

            $stmt = $this->db->prepare("UPDATE help_log SET receive_flag = 1,consent_flag = 0 WHERE help_id = :help_id and consent_flag = 1");
            $stmt->bindParam(':help_id', $help_id);
            $stmt->execute();
            $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "承認しました";
            
        }elseif (isset($_POST["consent_mission_Y"])){
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
            
        } elseif (isset($_POST["consent_debt_Y"])){
            $debt_id = $_POST["consent_debt_id"];
            if(!empty($_POST["interest"])){
                $interest = $_POST["interest"];         
            } else {
                $_SESSION["interest_error"] = "*利率を入力してください。";
                header('Location: consent.php'); 
                exit();
            }
            

            $query = "SELECT debt_amount, installments FROM debt WHERE debt_id = :debt_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':debt_id', $debt_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $debt_amount = $result['debt_amount'];
            $installments = $result['installments'];

            $repayment_amount = $debt_amount * (1 + ($interest / 100));
            $repayment_installments = ceil($repayment_amount / $installments);

            $stmt = $this->db->prepare("UPDATE debt SET approval_flag = 1, repayment_amount = :repayment_amount, interest = :interest, repayment_installments = :repayment_installments WHERE debt_id = :debt_id");
            $stmt->bindParam(':repayment_amount', $repayment_amount);
            $stmt->bindParam(':interest', $interest);
            $stmt->bindParam(':repayment_installments', $repayment_installments);
            $stmt->bindParam(':debt_id', $debt_id);
            $stmt->execute();

            $child_data_id = $this->get_child_data_id($debt_id);

            $stmt = $this->db->prepare("UPDATE child_data SET savings = savings + :amount WHERE child_data_id = :child_data_id");
            $stmt->bindParam(':amount', $debt_amount, PDO::PARAM_STR);
            $stmt->bindParam(':child_data_id', $child_data_id, PDO::PARAM_INT);
            $stmt->execute();


            echo "承認しました";

        //銀行拒否
        } elseif (isset($_POST["consent_debt_N"])){
            $debt_id = $_POST["consent_debt_id"];
            $stmt = $this->db->prepare("DELETE FROM debt WHERE debt_id = :debt_id");
            $stmt->bindParam(':debt_id', $debt_id);
            $stmt->execute();

            echo "拒否しました";

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

    public function display_consent_debt($family_id) {
        // データベースクエリを実行して、指定されたユーザーIDおよびファミリーIDに関連するデータを取得
        $query = "SELECT * FROM debt WHERE family_id = :family_id AND approval_flag = 0";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':family_id', $family_id, PDO::PARAM_INT);
        $stmt->execute();
        
        // データを連想配列として取得
        $debts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $debts;
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

    public function debt_select($debt_id) {
        $query = "SELECT user_id FROM debt WHERE debt_id = :debt_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':debt_id', $debt_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            $debtuser = $result['user_id'];
            $query = "SELECT first_name FROM user WHERE user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $debtuser, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($result) {
                $first_name = $result['first_name'];
                echo $first_name;
            } else {
                echo "ユーザーが見つかりません";
            }
        } else {
            echo "データが見つかりません";
        }
    }

    public function get_child_data_id($debt_id) {
        // debtテーブルからuser_idを取得
        $query = "SELECT user_id FROM debt WHERE debt_id = :debt_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':debt_id', $debt_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $debtuser = $result['user_id'];

            // child_dataテーブルからchild_data_idを取得
            $query_child_data = "SELECT child_data_id FROM child_data WHERE user_id = :user_id";
            $stmt_child_data = $this->db->prepare($query_child_data);
            $stmt_child_data->bindParam(':user_id', $debtuser, PDO::PARAM_INT);
            $stmt_child_data->execute();
            $result_child_data = $stmt_child_data->fetch(PDO::FETCH_ASSOC);

            if ($result_child_data) {
                $child_data_id = $result_child_data['child_data_id'];
                return $child_data_id;
            } else {
                return "child_dataが見つかりません";
            }
        } else {
            return "データが見つかりません";
        }
    }
}
?>