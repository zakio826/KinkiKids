<?php
date_default_timezone_set('Asia/Tokyo');

class mission {
    private $db;
    private $error;

    function __construct($db) {
        $this->db = $db;
        $this->error = []; // 初期化
        if (isset($_POST["delete_mission_id"])) {
            $this->DeletemissionToDatabase($_POST["delete_mission_id"]);
        }elseif(isset($_POST['consent_mission_id'])){
            $this->consentmissionToDatabase($_POST["consent_mission_id"]);
        }else if(isset($_POST["e_mission_id"])){
            if ($_POST['e_mission_name'] === "") {
                $this->error['e_mission_name'] = "blank";
            }
            if ($_POST['e_get_point'] === "") {
                $this->error['e_get_point'] = "blank";
            }
            if (isset($_POST['e_mission_person'])) {
                $e_person = $_POST['e_mission_person'];
            } else {
                $this->error['e_mission_person'] = "blank";
            }
        
            // エラーがなければ処理
            if (empty($this->error)) {
                $this->updatemission($_POST["e_mission_id"],$_POST['e_mission_name'],$_POST['e_get_point'],$e_person);
                header('Location: ./mission_add.php'); 
                exit();
            }
        }elseif(isset($_POST["mission_name"]) && isset($_POST["mission_get_point"])){
            //nullチェック
            if ($_POST['mission_name'] === "") {
                $this->error['mission_name'] = "blank";
            }
            if ($_POST['mission_get_point'] === "") {
                $this->error['mission_get_point'] = "blank";
            }
            if (!isset($_POST['mission_person'])) {
                $this->error['mission_person'] = "blank";
            } 
            

            if (empty($this->error)) {
                $m_name =$_POST['mission_name'];
                $m_get_point = $_POST['mission_get_point'];
                $m_persons = $_POST['mission_person'];
                $this->missionToDatabase($m_name,$m_get_point);
                //LINEメッセージ登録も↓でします
                $this->missionpersonToDatabase($m_name,$m_persons);
            }else{
                //TODO エラー処理
            }
        }
    }
    public function display_mission($family_id) {
        $stmt = $this->db->prepare("SELECT * FROM mission WHERE family_id = :family_id and display_flag = 1 ORDER BY mission_id DESC");
        $stmt->bindParam(':family_id', $family_id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function child_select($allc) {
        $stmt = $this->db->prepare("SELECT user_id,first_name,role_id FROM user WHERE family_id = :family_id");
        $stmt->bindParam(':family_id', $_SESSION["family_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $person) {
            if (floor($person['role_id'] / 10 ) == 3) {
                echo "<input type='checkbox' name='mission_person[]' value=".$person['user_id']." ".$allc.">";
                echo $person['first_name']."　";
            }
        }
    }

    public function missionToDatabase($m_name,$m_get_point){
        $user_id = $_SESSION["user_id"];
        $family_id = $_SESSION["family_id"];

        $stmt = $this->db->prepare("INSERT INTO mission (user_id,family_id,mission_name,get_point,display_flag) VALUES (:user_id,:family_id,:mission_name,:get_point,1)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':family_id', $family_id);
        $stmt->bindParam(':mission_name', $m_name);
        $stmt->bindParam(':get_point', $m_get_point);
        $stmt->execute();
        $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "登録完了";
    }
    public function missionpersonToDatabase($m_name,$m_persons){
        $user_id = $_SESSION["user_id"];
        $dtime = date("Y-m-d H:i:s");

        $stmt2 = $this->db->prepare("SELECT mission_id FROM mission ORDER BY mission_id DESC LIMIT 1");
        $stmt2->execute();
        $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        $mission_id = (int)$result2[0]["mission_id"];

        foreach ($m_persons as $person) {
            $stmt = $this->db->prepare("INSERT INTO mission_person (mission_id,user_id) VALUES (:mission_id, :user_id)");
            $stmt->bindParam(':mission_id', $mission_id);
            $stmt->bindParam(':user_id', $person);
            $stmt->execute();
            $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = $this->db->prepare("INSERT INTO line_message (sender_id,receiver_id,messagetext,sent_time) VALUES (:sender_id, :receiver_id,:messagetext,:sent_time)");
            $stmt->bindParam(':sender_id', $user_id);
            $stmt->bindParam(':receiver_id', $person);
            $stmt->bindParam(':messagetext', $m_name);
            $stmt->bindParam(':sent_time', $dtime);
            $stmt->execute();
            $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function person_select($mission_id) {
        $stmt = $this->db->prepare("SELECT user.first_name FROM user
                                    INNER JOIN mission_person ON user.user_id = mission_person.user_id 
                                    WHERE mission_person.mission_id = :mission_id");
        $stmt->bindParam(':mission_id', $mission_id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $first_flag = 0;
        foreach ($result as $person) {
            if ($first_flag != 0) { echo ","; }
            $first_flag++;

            echo $person['first_name'];
        }
    }
    public function DeletemissionToDatabase($mission_id) {
        $stmt = $this->db->prepare("DELETE FROM mission WHERE mission_id = :mission_id");
        $stmt->bindParam(':mission_id', $mission_id);
        $stmt->execute();
        $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "削除しました";
    }

    public function m_consent_button($mission_id) {
        $stmt = $this->db->prepare("SELECT consent_flag FROM mission_log WHERE mission_id = :mission_id ORDER BY mission_log_id DESC LIMIT 1");
        $stmt->bindParam(':mission_id', $mission_id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (isset($result[0])) {
            if($result[0]['consent_flag'] == 1) {
                echo "承認待ち";
            } else {
                echo "<button type='submit'>やりました！</button>";
            } 
        } else {
            echo "<button type='submit'>やりました！</button>";
        }   
    }

    public function consentmissionToDatabase($mission_id) {
        if(isset($_SESSION["user_id"])){
            $user_id = $_SESSION["user_id"];
            $dtime = date("Y-m-d H:i:s");

            $stmt = $this->db->prepare("INSERT INTO mission_log (user_id, mission_id, mission_day, consent_flag,receive_flag) VALUES (:user_id, :mission_id, :dtime, 1,0)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':mission_id', $mission_id);
            $stmt->bindParam(':dtime', $dtime);
            $stmt->execute();
            $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<p>承認待ち</p>";
            $line_id = $this->getLineId($mission_id); // ユーザーのLINE IDを取得するメソッドを呼び出す
            if($line_id){
                $result = $this->MessageGet($user_id,$mission_id);
                $message = "緊急ミッションが完了しました。\n".$result;
                $this->sendLineNotification($line_id, $message,$mission_id); // LINEBOTに通知を送るメソッドを呼び出す
            } 
        }
    }

    public function getmissionInfo($mission_id) {
        $stmt = $this->db->prepare("SELECT * FROM mission WHERE mission_id = :mission_id");
        $stmt->bindParam(':mission_id', $mission_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function e_child_select($mission_id) {
        if (isset($_SESSION["family_id"])) {
            $stmt = $this->db->prepare("SELECT user_id,first_name,role_id FROM user WHERE family_id = :family_id");
            $stmt->bindParam(':family_id', $_SESSION["family_id"]);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt2 = $this->db->prepare("SELECT user_id FROM mission_person WHERE mission_id = :mission_id");
            $stmt2->bindParam(':mission_id', $mission_id);
            $stmt2->execute();
            $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            $checked_li = [];
            foreach ($result2 as $id) {
                array_push($checked_li,$id['user_id']);
            }

            foreach ($result as $person) {
                if (floor($person['role_id'] / 10 ) == 3) {
                    $checked = "";
                    if(in_array($person['user_id'], $checked_li)){
                        $checked = "checked";
                    }
                    echo "<input type='checkbox' name='e_mission_person[]' value=".$person['user_id'];
                    echo " ".$checked.">";
                    echo $person['first_name']."　";
                }
            }
        } else {
            //TODO ログインしていない
        }
    }
    public function updatemission($mission_id,$mission_name,$get_point,$mission_person) {

        $user_id = (int)$_SESSION['user_id'];
        $family_id = (int)$_SESSION['family_id'];
        
        $stmt = $this->db->prepare("UPDATE mission SET mission_name = :mission_name , get_point = :get_point  WHERE mission_id = :mission_id");
        $stmt->bindParam(':mission_name', $mission_name);
        $stmt->bindParam(':get_point', $get_point);
        $stmt->bindParam(':mission_id', $mission_id);
        $stmt->execute();
        $stmt->fetchAll(PDO::FETCH_ASSOC);

        //personの処理
        //前に登録してたperson全部消す
        $stmt2 = $this->db->prepare("DELETE FROM mission_person WHERE mission_id = :mission_id");
        $stmt2->bindParam(':mission_id', $mission_id);
        $stmt2->execute();
        $stmt2->fetchAll(PDO::FETCH_ASSOC);
        //今回のperson登録
        foreach ($mission_person as $person) {
            $stmt = $this->db->prepare("INSERT INTO mission_person (mission_id,user_id) VALUES (:mission_id, :user_id)");
            $stmt->bindParam(':mission_id', $mission_id);
            $stmt->bindParam(':user_id', $person);
            $stmt->execute();
            $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }


    private function getLineId($mission_id) {
        // helpテーブルからuser_idを取得
        $stmt = $this->db->prepare("SELECT user_id FROM mission WHERE mission_id = :mission_id");
        $stmt->bindParam(':mission_id', $mission_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // LINEdatabaseからUIDを取得
        $stmt2 = $this->db->prepare("SELECT UID FROM LINEdatabase WHERE id = :user_id");
        $stmt2->bindParam(':user_id', $result['user_id']);
        $stmt2->execute();
        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        
        if(isset($result2['UID'])) {
            return $result2['UID'];
        } else {
            return null;
        }
    }
    private function MessageGet($user_id, $mission_id) {
        $stmt = $this->db->prepare("SELECT mission_name FROM mission WHERE mission_id = :mission_id");
        $stmt->bindParam(':mission_id', $mission_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // LINEdatabaseからUIDを取得
        $stmt2 = $this->db->prepare("SELECT first_name FROM user WHERE user_id = :user_id");
        $stmt2->bindParam(':user_id', $user_id);
        $stmt2->execute();
        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                
        return "送信者:".$result2['first_name'] . "\n内容:" . $result['mission_name']; 
    }

    private function sendLineNotification($line_id, $message, $mission_id) {
        // LINE Messaging API SDKの読み込み
        require_once(__DIR__ . '/vendor/autoload.php');
    
        // LINE BOTの設定
        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient('Kze+ZgLB4x9rAdf5+UQ8Iv23kGgEWm+E3J13IuZY4KJ6SXkbR/6UE6UtcA5u7BLkvZI5Vo5654ZdzHs9DEuUJ/arEYPV7Saw/s+upXosGKuAYT3KtEq9itfyK60iBvAAJkkvF0CLPUP9YYG6c6aupQdB04t89/1O/w1cDnyilFU=');
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => '78143eef9ac1707bb475fa8813339356']);
    
        // メッセージの送信
        $textMessage = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
        $bot->pushMessage($line_id, $textMessage);
    
        // リンクの直接メッセージとして送信
        $linkMessage = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("確認してね\n".'https://kinkikids.sub.jp/src/app/point/consent.php?id=' . $line_id);
        $bot->pushMessage($line_id, $linkMessage);
    }

    public function person_error() {
        if (!empty($this->error['mission_person'])) {
            switch ($this->error['mission_person']) {
                //子供が選択されてなければエラーを表示
                case 'blank': echo '*子供を選択してください。'; break;
            }
        }
    }

    public function missionname_error() {
        if (!empty($this->error['mission_name'])) {
            switch ($this->error['mission_name']) {
                //ミッション名が入力されていなければエラーを表示
                case 'blank': echo '*ミッション名を入力してください。'; break;
            }
        }
    }

    public function point_error() {
        if (!empty($this->error['mission_get_point'])) {
            switch ($this->error['mission_get_point']) {
                //獲得ポイントが入力されていなければエラーを表示
                case 'blank': echo '*獲得ポイントを入力してください。'; break;
            }
        }
    }

}