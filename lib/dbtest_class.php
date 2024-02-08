<?php
class test
{
    private $db;
    private $error; 
    function __construct($db){
        $this->db = $db;
        $this->error = []; // 初期化
    }

    public function test() {//TODO　DB処理改善できる
        $stmt = $this->db->prepare("SELECT user.first_name FROM user
                                        INNER JOIN help_person ON user.user_id = help_person.user_id
                                        WHERE help_id = 23");
        //$stmt->bindParam(':help_id', $help_id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //var_dump($result);
        $first_flag = 0;
        foreach($result as $row){
            if ($first_flag != 0){
                echo ",";
            }
            $first_flag = 1;
            echo $row['first_name'];
        }
    }
}
?>