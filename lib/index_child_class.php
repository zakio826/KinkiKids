<?php
// test
class index_child_class{
    private $error; // エラー情報を保持するプロパティ
    private $db; // データベース接続を保持するプロパティ
    
    function __construct($db){
        $this->db = $db;
        $this->error = []; // 初期化
    }
    
    public function getHelp($i){
        $stmt = $this->db->prepare("SELECT * FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $family_id = $result['family_id'];

        $stmt = $this->db->prepare("SELECT * FROM help WHERE family_id = :family_id");
        $stmt->bindParam(':family_id', $_SESSION["family_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $help_name = $result[$i]['help_name'];
    
        return $help_name;
    }
    public function getHelpCount(){
        $stmt = $this->db->prepare("SELECT * FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $family_id = $result['family_id'];

        $stmt = $this->db->prepare("SELECT * FROM help WHERE family_id = :family_id");
        $stmt->bindParam(':family_id', $_SESSION["family_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $help_count = count($result);
    
        return $help_count;
    }
    
    public function getHave_points(){
        $stmt = $this->db->prepare("SELECT have_points FROM child_data WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(count($result) != 0){
            return $result[0]['have_points'];
        } else {
            return 0;
        }
    }
    public function getSavings(){
        $stmt = $this->db->prepare("SELECT savings FROM child_data WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['savings'];

    }
    public function getGoalCount(){
        $stmt = $this->db->prepare("SELECT * FROM goal WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return count($result);
    }
    public function getTarget_amount(){
        
        $stmt = $this->db->prepare("SELECT * FROM goal WHERE user_id = :user_id order by goal_deadline asc");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($result as $deadline){
            $date01 = new DateTime('now');
            $date02 = new DateTime($deadline['goal_deadline']);
            if($date01 <= $date02){
                return $deadline['target_amount'];
            }
        }
    }
    public function getGoal_deadline(){
        $stmt = $this->db->prepare("SELECT * FROM goal WHERE user_id = :user_id order by goal_deadline asc");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $deadline){
            $date01 = new DateTime('now');
            $date02 = new DateTime($deadline['goal_deadline']);
            if($date01 <= $date02){
                return $deadline['goal_deadline'];
            }
        }
    }
    public function getGoal_detail(){
        $stmt = $this->db->prepare("SELECT * FROM goal WHERE user_id = :user_id order by goal_deadline asc");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $deadline){
            $date01 = new DateTime('now');
            $date02 = new DateTime($deadline['goal_deadline']);
            if($date01 <= $date02){
                return $deadline['goal_detail'];
            }
        }
    }
    public function getRequired_point(){
        $stmt = $this->db->prepare("SELECT * FROM goal WHERE user_id = :user_id order by goal_deadline asc");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $deadline){
            $date01 = new DateTime('now');
            $date02 = new DateTime($deadline['goal_deadline']);
            if($date01 <= $date02){
                
                $date01 = new DateTime('now');
                $date02 = new DateTime($deadline['goal_deadline']);
                $diff = date_diff($date01, $date02);
                
                $target_amount = $deadline['target_amount'];
                
                $stmt = $this->db->prepare("SELECT have_points FROM child_data WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $_SESSION["user_id"]);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(count($result) != 0){
                    $have_points = $result[0]['have_points'];
                } else {
                    $have_points = 0;
                }
        
        

                $stmt = $this->db->prepare("SELECT savings FROM child_data WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $_SESSION["user_id"]);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(count($result) != 0){
                    $savings = $result[0]['savings'];
                } else {
                    $savings = 0;
                }

                
                $stmt = $this->db->prepare("SELECT allowance_amount FROM allowance WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $_SESSION["user_id"]);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $allowance_amount = $result['allowance_amount'];
                
                $answer = $target_amount - $have_points - $savings - $allowance_amount * $diff->m;
                
                if ($answer >= 0){
                    return $answer;
                } else {
                    return 0;
                }
            }
        }
    }
    public function getOnerequired_point(){
        $stmt = $this->db->prepare("SELECT * FROM goal WHERE user_id = :user_id order by goal_deadline asc");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $deadline){
            $date01 = new DateTime('now');
            $date02 = new DateTime($deadline['goal_deadline']);
            if($date01 <= $date02){

                
                $date01 = new DateTime('now');
                $date02 = new DateTime($deadline['goal_deadline']);
                $diff = date_diff($date01, $date02);
                
                $diff2 = $date01->diff($date02);
                
                $target_amount = $deadline['target_amount'];
                
                $stmt = $this->db->prepare("SELECT have_points FROM child_data WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $_SESSION["user_id"]);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(count($result) != 0){
                    $have_points = $result[0]['have_points'];
                } else {
                    $have_points = 0;
                }
                
                $stmt = $this->db->prepare("SELECT savings FROM child_data WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $_SESSION["user_id"]);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(count($result) != 0){
                    $savings = $result[0]['savings'];
                } else {
                    $savings = 0;
                }
                
                $stmt = $this->db->prepare("SELECT allowance_amount FROM allowance WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $_SESSION["user_id"]);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $allowance_amount = $result['allowance_amount'];
                
                $answer = ceil(($target_amount - $have_points - $savings - $allowance_amount * $diff->m) / $diff2->format('%a'));
                
                if ($answer >= 0){
                    return $answer;
                } else {
                    return 0;
                }
            }
        }
    }
}

?>