<?php
date_default_timezone_set('Asia/Tokyo');

class help {
    private $db;
    private $error;

    function __construct($db) {
        $this->db = $db;
        $this->error = []; // 初期化

        if (!empty($_POST)) {
            if (isset($_POST["delete_help_id"])) {
                $this->DeleteHelpToDatabase($_POST["delete_help_id"]);
            } else if (isset($_POST["consent_help_id"])) {
                $this->consentHelpToDatabase($_POST["consent_help_id"]);
            } else if (!isset($_POST["narrow"])){

                // 入力情報に空白がないか検知
                if ($_POST['help_name'] === "") {
                    $error['help_name'] = "blank";
                }
                if ($_POST['get_point'] === "") {
                    $error['get_point'] = "blank";
                }
                if (isset($_POST['help_person'])) {
                    $person = $_POST['help_person'];
                } else {
                    $error['get_person'] = "blank";
                }
                

            
                // エラーがなければ次のページへ
                if (!isset($error)) {
                    $_SESSION['join'] = $_POST;

                    $user_id = $_SESSION["user_id"];

                    $family_id = $this->getFamilyId($user_id);

                    $_SESSION['join']['user_id'] = $user_id;
                    $_SESSION['join']['family_id'] = $family_id;

                    $this->saveHelpToDatabase();
                    $this->saveHelppersonToDatabase($person);

                    header('Location: ./help_add.php'); exit();
                }
            }
        }

    }

    // ユーザーのfamily_idを取得する関数
    private function getFamilyId($user_id){
        $stmt = $this->db->prepare("SELECT family_id FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['family_id'];
    }

    private function saveHelpToDatabase() {
        $sql = "INSERT INTO help (user_id, family_id, help_name, get_point,stop_flag) ".
               "VALUES (:user_id, :family_id, :help_name, :get_point,1)";
    
        $params = array(
            ':user_id' => $_SESSION['join']['user_id'],
            ':family_id' => $_SESSION['join']['family_id'],
            ':help_name' => $_SESSION['join']['help_name'],
            ':get_point' => $_SESSION['join']['get_point']
        );
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }

    private function saveHelppersonToDatabase($persons) {
        // $help_id = 5;
        $stmt2 = $this->db->prepare("SELECT help_id FROM help ORDER BY help_id DESC LIMIT 1");
        $stmt2->execute();
        $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        $help_id = (int) $result2[0]["help_id"];

        foreach ($persons as $person) {
            echo $person;

            $stmt = $this->db->prepare("INSERT INTO help_person (help_id,user_id) VALUES (:help_id, :user_id)");
            $stmt->bindParam(':help_id', $help_id);
            $stmt->bindParam(':user_id', $person);
            $stmt->execute();
            $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function display_help($family_id) {
        $stmt = $this->db->prepare("SELECT * FROM help WHERE family_id = :family_id and stop_flag = 1");
        $stmt->bindParam(':family_id', $family_id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    private function DeleteHelpToDatabase($help_id) {
        $stmt = $this->db->prepare("DELETE FROM help WHERE help_id = :help_id");
        $stmt->bindParam(':help_id', $help_id);
        $stmt->execute();
        $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function consentHelpToDatabase($help_id) {
        if(isset($_SESSION["user_id"])){
            $user_id = $_SESSION["user_id"];
            $dtime = date("Y-m-d H:i:s");
    
            $stmt = $this->db->prepare("INSERT INTO help_log (user_id, help_id, help_day, consent_flag,receive_flag) VALUES (:user_id, :help_id, :dtime, 1,0)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':help_id', $help_id);
            $stmt->bindParam(':dtime', $dtime);
            $stmt->execute();
            $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            echo "<p>承認待ち</p>"; // TODO 承認待ちの処理
    
            // LINEBOTへの通知処理
            $line_id = $this->getLineId($help_id); // ユーザーのLINE IDを取得するメソッドを呼び出す
            if($line_id){
                $result = $this->MessageGet($user_id,$help_id);
                $message = "お手伝いが完了しました。\n".$result;
    
                $this->sendLineNotification($line_id, $message,$help_id); // LINEBOTに通知を送るメソッドを呼び出す
            } 
        } else {
            //TODO ログインしていない
        }
    }
    
    private function getLineId($help_id) {
        // helpテーブルからuser_idを取得
        $stmt = $this->db->prepare("SELECT user_id FROM help WHERE help_id = :help_id");
        $stmt->bindParam(':help_id', $help_id);
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
    
    

    private function MessageGet($user_id, $help_id) {
        $stmt = $this->db->prepare("SELECT help_name,get_point FROM help WHERE help_id = :help_id");
        $stmt->bindParam(':help_id', $help_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // LINEdatabaseからUIDを取得
        $stmt2 = $this->db->prepare("SELECT first_name FROM user WHERE user_id = :user_id");
        $stmt2->bindParam(':user_id', $user_id);
        $stmt2->execute();
        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                
        return "送信者:".$result2['first_name'] . "\n内容:" . $result['help_name']."\nポイント".$result['get_point'].'pt'; 
    }

    private function sendLineNotification($line_id, $message, $help_id) {
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

    public function child_select() {
        if (isset($_SESSION["family_id"])) {
            $stmt = $this->db->prepare("SELECT user_id,first_name,role_id FROM user WHERE family_id = :family_id");
            $stmt->bindParam(':family_id', $_SESSION["family_id"]);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $person) {
                if (floor($person['role_id'] / 10 ) == 3) {
                    echo "<input type='checkbox' name='help_person[]' value=".$person['user_id'].">";
                    echo $person['first_name']."　";
                }
            }
        } else {
            //TODO ログインしていない
        }
    }

    public function e_child_select($help_id) {
        if (isset($_SESSION["family_id"])) {
            $stmt = $this->db->prepare("SELECT user_id,first_name,role_id FROM user WHERE family_id = :family_id");
            $stmt->bindParam(':family_id', $_SESSION["family_id"]);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt2 = $this->db->prepare("SELECT user_id FROM help_person WHERE help_id = :help_id");
            $stmt2->bindParam(':help_id', $help_id);
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
                    echo "<input type='checkbox' name='help_person[]' value=".$person['user_id'];
                    echo " ".$checked.">";
                    echo $person['first_name']."　";
                }
            }
        } else {
            //TODO ログインしていない
        }
    }

    public function getHelpInfo($help_id) {
        $stmt = $this->db->prepare("SELECT * FROM help WHERE help_id = :help_id");
        $stmt->bindParam(':help_id', $help_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateHelp($data) {
        $help_id = $data['help_id'];
        $help_name = $data['help_name'];
        $get_point = $data['get_point'];

        $user_id = (int)$_SESSION['user_id'];
        $family_id = (int)$_SESSION['family_id'];

        $stmt = $this->db->prepare("INSERT INTO help (user_id, family_id, help_name, get_point,stop_flag) VALUES (:user_id, :family_id, :help_name, :get_point,1)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':family_id', $family_id);
        $stmt->bindParam(':help_name', $help_name);
        $stmt->bindParam(':get_point', $get_point);
        $stmt->execute();
        $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt2 = $this->db->prepare("UPDATE help SET stop_flag = 0 WHERE help_id = :help_id");
        $stmt2->bindParam(':help_id', $help_id);
        $stmt2->execute();
        $stmt2->fetchAll(PDO::FETCH_ASSOC);
    }

    public function person_select($help_id) {
        $stmt = $this->db->prepare("SELECT user_id FROM help_person WHERE help_id = :help_id ");
        $stmt->bindParam(':help_id', $help_id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $first_flag = 0;
        foreach ($result as $person) {
            if ($first_flag != 0) { echo ","; }
            $first_flag++;

            $stmt2 = $this->db->prepare("SELECT first_name FROM user WHERE user_id = :user_id");
            $stmt2->bindParam(':user_id', $person['user_id']);
            $stmt2->execute();
            $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            echo $result2[0]['first_name'];
        }
    }

   
    public function consent_button($help_id) {
        $stmt = $this->db->prepare("SELECT consent_flag FROM help_log WHERE help_id = :help_id ORDER BY help_log_id DESC LIMIT 1");
        $stmt->bindParam(':help_id', $help_id);
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

    public function narrow_down() {
        $users = array(); // ユーザーリストの初期化
    
        if (isset($_SESSION["family_id"])) {
            $stmt = $this->db->prepare("SELECT user_id, first_name, role_id FROM user WHERE family_id = :family_id");
            $stmt->bindParam(':family_id', $_SESSION["family_id"]);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            foreach ($result as $person) {
                if (floor($person['role_id'] / 10 ) == 3) {
                    $users[] = $person; // ユーザーリストにユーザーを追加
                }
            }
        } else {
            // ログインしていない場合の処理
        }

        return $users; // ユーザーリストを返す
    }

    public function getHelpsByUserId($userId) {
        try {
            $query = "SELECT * FROM help_person ".
                     "INNER JOIN help ON help_person.help_id = help.help_id ".
                     "WHERE help_person.user_id = :userId AND help.stop_flag = 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            
            return false;
        }
    }

}
?>
