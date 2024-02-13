<?php
// test
class index_child_class {
    private $error; // エラー情報を保持するプロパティ
    private $db; // データベース接続を保持するプロパティ

    
    
    function __construct($db, $user_id) {
        $this->db = $db;
        $this->error = []; // 初期化
    }
    
    function message($db) {
            $today = new DateTime('now');
            if (!empty($_POST['check'])) {
                $statement = $db->prepare("INSERT INTO line_message SET sender_id=?, receiver_id=?, messagetext=?, sent_time=?");
                $statement->execute(array($_SESSION["user_id"], $_POST['receiver'], $_POST['message'], $today->format('Y-m-d H:i:s')));
            }    

    }
    public function getFamilyUser() {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $family_id = $result['family_id'];

        $stmt = $this->db->prepare("SELECT * FROM user WHERE family_id = :family_id AND NOT user_id = :user_id");
        $stmt->bindParam(':family_id', $family_id);
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $record) {
            echo '<option value="' . $record['user_id'] . '">' . $record['first_name'] . "</option>";
        }
    }

    public function getMessageCount() {
        $stmt = $this->db->prepare("SELECT * FROM line_message WHERE sender_id = :user_id OR receiver_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return count($result);
    }

    public function getMessage($i) {
        $stmt = $this->db->prepare("SELECT * FROM line_message WHERE sender_id = :user_id OR receiver_id = :user_id order by sent_time desc");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $message = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->db->prepare("SELECT * FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $message[$i]['sender_id']);
        $stmt->execute();
        $sender = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $this->db->prepare("SELECT * FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $message[$i]['receiver_id']);
        $stmt->execute();
        $receiver = $stmt->fetch(PDO::FETCH_ASSOC);

        return array(
            'session_user' => $_SESSION["user_id"],
            'messagetext' => $message[$i]['messagetext'],
            'sent_time' => $message[$i]['sent_time'],
            'sender' => $sender['first_name'],
            'sender_id' => $sender['user_id'],
            'receiver' => $receiver['first_name'],
            'receiver_id' => $receiver['user_id'],
        );
    }

    public function getHelp($i) {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $family_id = $result['family_id'];

        $stmt = $this->db->prepare("SELECT * FROM help WHERE family_id = :family_id");
        $stmt->bindParam(':family_id', $family_id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $help_name = $result[$i]['help_name'];
    
        return $help_name;
    }

    public function getHelpCount() {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $family_id = $result['family_id'];

        $stmt = $this->db->prepare("SELECT * FROM help WHERE family_id = :family_id");
        $stmt->bindParam(':family_id', $family_id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $help_count = count($result);
    
        return $help_count;
    }
    
    public function getHave_points() {
        $stmt = $this->db->prepare("SELECT have_points FROM child_data WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($result) != 0) {
            return $result[0]['have_points'];
        } else {
            return 0;
        }
    }

    public function getSavings() {
        $stmt = $this->db->prepare("SELECT * FROM child_data WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($result) != 0) {
            return $result[0]['savings'];
        } else {
            return 0;
        }
    }

    public function getGoalCount() {
        $stmt = $this->db->prepare("SELECT * FROM goal WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return count($result);
    }

    public function getTarget_amount() {
        $stmt = $this->db->prepare("SELECT * FROM goal WHERE user_id = :user_id order by goal_deadline asc");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($result as $deadline) {
            $date01 = new DateTime('now');
            $date02 = new DateTime($deadline['goal_deadline']);
            if($date01->format('Y-m-d') <= $date02->format('Y-m-d')){
                return $deadline['target_amount'];
            }
        }
    }

    public function getGoal_deadline() {
        $stmt = $this->db->prepare("SELECT * FROM goal WHERE user_id = :user_id order by goal_deadline asc");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $deadline) {
            $date01 = new DateTime('now');
            $date02 = new DateTime($deadline['goal_deadline']);
            if($date01->format('Y-m-d') <= $date02->format('Y-m-d')){
                return $deadline['goal_deadline'];
            }

        }
    }

    public function getGoal_detail() {
        $stmt = $this->db->prepare("SELECT * FROM goal WHERE user_id = :user_id order by goal_deadline asc");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $deadline) {
            $date01 = new DateTime('now');
            $date02 = new DateTime($deadline['goal_deadline']);
            if($date01->format('Y-m-d') <= $date02->format('Y-m-d')){
                return $deadline['goal_detail'];
            }

            
        }
    }

    public function getRequired_point() {
        $stmt = $this->db->prepare("SELECT * FROM goal WHERE user_id = :user_id order by goal_deadline asc");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $deadline) {
            $date01 = new DateTime('now');
            $date02 = new DateTime($deadline['goal_deadline']);
            if($date01->format('Y-m-d') <= $date02->format('Y-m-d')){
                
                $date01 = new DateTime('now');
                $date02 = new DateTime($deadline['goal_deadline']);
                $diff = date_diff($date01, $date02);
                
                $target_amount = $deadline['target_amount'];
                
                $stmt = $this->db->prepare("SELECT have_points FROM child_data WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $_SESSION["user_id"]);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($result) != 0) {
                    $have_points = $result[0]['have_points'];
                } else {
                    $have_points = 0;
                }

                $stmt = $this->db->prepare("SELECT savings FROM child_data WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $_SESSION["user_id"]);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($result) != 0) {
                    $savings = $result[0]['savings'];
                } else {
                    $savings = 0;
                }
                
                $stmt = $this->db->prepare("SELECT allowance_amount FROM allowance WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $_SESSION["user_id"]);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(count($result) != 0){
                    $allowance_amount = $result['allowance_amount'];
                } else {
                    $allowance_amount = 0;
                }

                
                $answer = $target_amount - $have_points - $savings - $allowance_amount * $diff->m;
                
                if ($answer >= 0) {
                    return $answer;
                } else {
                    return 0;
                }
            }
        }
    }

    public function getOnerequired_point() {
        $stmt = $this->db->prepare("SELECT * FROM goal WHERE user_id = :user_id order by goal_deadline asc");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $deadline) {
            $date01 = new DateTime('now');
            $date02 = new DateTime($deadline['goal_deadline']);
            if($date01->format('Y-m-d') <= $date02->format('Y-m-d')){

                
                $date01 = new DateTime('now');
                $date02 = new DateTime($deadline['goal_deadline']);
                $diff = date_diff($date01, $date02);
                
                $diff2 = $date01->diff($date02);
                
                $target_amount = $deadline['target_amount'];
                
                $stmt = $this->db->prepare("SELECT have_points FROM child_data WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $_SESSION["user_id"]);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($result) != 0) {
                    $have_points = $result[0]['have_points'];
                } else {
                    $have_points = 0;
                }
                
                $stmt = $this->db->prepare("SELECT savings FROM child_data WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $_SESSION["user_id"]);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($result) != 0) {
                    $savings = $result[0]['savings'];
                } else {
                    $savings = 0;
                }
                
                $stmt = $this->db->prepare("SELECT allowance_amount FROM allowance WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $_SESSION["user_id"]);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(count($result) != 0){
                    $allowance_amount = $result['allowance_amount'];
                } else {
                    $allowance_amount = 0;
                }
                if($diff2->format('%a')>0){
                    $answer = ceil(($target_amount - $have_points - $savings - $allowance_amount * $diff->m) / $diff2->format('%a'));
                }else{
                    $answer = ceil(($target_amount - $have_points - $savings - $allowance_amount * $diff->m));
                }
                
                if ($answer >= 0) {
                    return $answer;
                } else {
                    return 0;
                }

            }
        }
    }

    public function display_consent_repayment($user_id) {
        $currentDate = date("d");
        $query = "SELECT * FROM debt WHERE user_id = :user_id AND approval_flag = 1 AND repayment_date = :current_date";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':current_date', $currentDate, PDO::PARAM_INT);
        $stmt->execute();
        
        // データを連想配列として取得
        $debts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $debts;
    }
}
?>