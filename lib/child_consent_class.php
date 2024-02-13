<?php
    date_default_timezone_set('Asia/Tokyo');
    class child_consent
    {
        private $db;
        private $error; 
        function __construct($db){
            $this->db = $db;
            $this->error = []; // 初期化

            //ポイント追加処理
            if(isset($_POST["child_consent_help_log_id"]) && isset($_POST["child_consent_user_id"]) && isset($_POST["child_consent_get_point"])){
                $help_log_id = $_POST["child_consent_help_log_id"];
                $user_id = $_POST["child_consent_user_id"];
                $get_point = $_POST["child_consent_get_point"];
                $stmt = $this->db->prepare("UPDATE help_log SET receive_flag = 0 WHERE help_log_id = :help_log_id");
                $stmt->bindParam(':help_log_id', $help_log_id);
                $stmt->execute();
                $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt2 = $this->db->prepare("UPDATE child_data SET have_points = have_points + :get_point WHERE user_id = :user_id");
                $stmt2->bindParam(':get_point', $get_point);
                $stmt2->bindParam(':user_id', $user_id);
                $stmt2->execute();
                $stmt2->fetchAll(PDO::FETCH_ASSOC);

                echo $get_point."ポイント獲得しました";
            }elseif(isset($_POST["child_consent_mission_log_id"]) && isset($_POST["child_consent_user_id"]) && isset($_POST["child_consent_get_point"])){
                $mission_log_id = $_POST["child_consent_mission_log_id"];
                $user_id = $_POST["child_consent_user_id"];
                $get_point = $_POST["child_consent_get_point"];
                $stmt = $this->db->prepare("UPDATE mission_log SET receive_flag = 0 WHERE mission_log_id = :mission_log_id");
                $stmt->bindParam(':mission_log_id', $mission_log_id);
                $stmt->execute();
                $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt2 = $this->db->prepare("UPDATE child_data SET have_points = have_points + :get_point WHERE user_id = :user_id");
                $stmt2->bindParam(':get_point', $get_point);
                $stmt2->bindParam(':user_id', $user_id);
                $stmt2->execute();
                $stmt2->fetchAll(PDO::FETCH_ASSOC);

                echo $get_point."ポイント獲得しました";
            }
        }
        public function getHelps($user_id){
            $stmt = $this->db->prepare("SELECT help_id,help_log_id FROM help_log WHERE user_id = :user_id and receive_flag = 1");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<li>";
            foreach($result as $row){
                $stmt2 = $this->db->prepare("SELECT * FROM help WHERE help_id = :help_id");
                $stmt2->bindParam(':help_id', $row['help_id']);
                $stmt2->execute();
                $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                echo "<strong>お手伝い名:</strong>".$result2[0]["help_name"]."<br>";
                echo "<strong>獲得ポイント:</strong>".$result2[0]["get_point"]."<br>";
                echo '
                <form action="" method="post">       
                        <input type="hidden" name="child_consent_help_log_id" value="'.$row["help_log_id"].'"> 
                        <input type="hidden" name="child_consent_user_id" value="'.$user_id.'">    
                        <input type="hidden" name="child_consent_get_point" value="'.$result2[0]["get_point"].'">  
                        <button type="submit">ポイント獲得</button>
                </form>
                ';
            }
            echo "</li>";
            if(empty($result)){
                return true;
            }else{
                return false;
            }
            
        }

        public function getmissions($user_id){
            //TODO ない時の処理？？？
            $stmt = $this->db->prepare("SELECT mission_id,mission_log_id FROM mission_log WHERE user_id = :user_id and receive_flag = 1");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<li>";
            foreach($result as $row){
                $stmt2 = $this->db->prepare("SELECT * FROM mission WHERE mission_id = :mission_id");
                $stmt2->bindParam(':mission_id', $row['mission_id']);
                $stmt2->execute();
                $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                echo "★緊急ミッション★<br>";
                echo "<strong>ミッション名:</strong>".$result2[0]["mission_name"]."<br>";
                echo "<strong>獲得ポイント:</strong>".$result2[0]["get_point"]."<br>";
                echo '
                <form action="" method="post">       
                        <input type="hidden" name="child_consent_mission_log_id" value="'.$row["mission_log_id"].'"> 
                        <input type="hidden" name="child_consent_user_id" value="'.$user_id.'">    
                        <input type="hidden" name="child_consent_get_point" value="'.$result2[0]["get_point"].'">  
                        <button type="submit">ポイント獲得</button>
                </form>
                ';
            }
            echo "</li>";
            if(empty($result)){
                return true;
            }else{
                return false;
            }
        }
    }