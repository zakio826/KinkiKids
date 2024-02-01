<?php
// test
class level_of_achievement_class{
    private $error; // エラー情報を保持するプロパティ
    private $db; // データベース接続を保持するプロパティ

    function __construct($db){
        $this->db = $db;
        $this->error = []; // 初期化
    }


    public function getHave_points(){
        $stmt = $this->db->prepare("SELECT have_points FROM child_data WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['have_points'];
    }
    public function getSavings(){
        $stmt = $this->db->prepare("SELECT savings FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['savings'];

    }
    public function getTarget_amount(){
        $stmt = $this->db->prepare("SELECT target_amount FROM goal WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['target_amount'];
    }
    public function getRequired_point(){
        $stmt = $this->db->prepare("SELECT goal_deadline FROM goal WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $date01 = new DateTime('now');
        $date02 = new DateTime($result['goal_deadline']);
        $diff = date_diff($date01, $date02);

        $stmt = $this->db->prepare("SELECT target_amount FROM goal WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $target_amount = $result['target_amount'];

        $stmt = $this->db->prepare("SELECT have_points FROM child_data WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $have_points = $result['have_points'];

        $stmt = $this->db->prepare("SELECT savings FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $savings = $result['savings'];

        $stmt = $this->db->prepare("SELECT allowance_amount FROM allowance WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $allowance_amount = $result['allowance_amount'];

        return $target_amount - $have_points - $savings - $allowance_amount * $diff->m;
    }
    public function getOnerequired_point(){
        $stmt = $this->db->prepare("SELECT goal_deadline FROM goal WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $date01 = new DateTime('now');
        $date02 = new DateTime($result['goal_deadline']);
        $diff = date_diff($date01, $date02);

        $stmt = $this->db->prepare("SELECT target_amount FROM goal WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $target_amount = $result['target_amount'];

        $stmt = $this->db->prepare("SELECT have_points FROM child_data WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $have_points = $result['have_points'];

        $stmt = $this->db->prepare("SELECT savings FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $savings = $result['savings'];

        $stmt = $this->db->prepare("SELECT allowance_amount FROM allowance WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $allowance_amount = $result['allowance_amount'];

        return ceil(($target_amount - $have_points - $savings - $allowance_amount * $diff->m) / $diff->d);
    }
}

?>
