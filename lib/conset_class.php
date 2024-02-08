<?php
    date_default_timezone_set('Asia/Tokyo');
    class consent
    {
        private $db;
        private $error; 
        function __construct($db){
            $this->db = $db;
            $this->error = []; // 初期化
        }
        public function display_consent_help($user_id) {//TODO　DB処理改善できる
            $stmt = $this->db->prepare("SELECT * FROM help WHERE user_id = :user_id and stop_flag = 1");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $rtn = [];
            $i = 0;
            foreach ($result as $row) {
                $stmt2 = $this->db->prepare("SELECT consent_flag FROM help_log WHERE help_id = :help_id and consent_flag = 1");
                $stmt2->bindParam(':help_id', $row['help_id']);
                $stmt2->execute();
                $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                if (isset($result2[0])){
                    $rtn[$i] = $row;
                    $i++;
                }
                
            }

            return $rtn;
        }
    }