<?php
class debt {
    private $db;

    function __construct($db, $user_id, $family_id) {
        $this->db = $db;
        $this->error = [];

        if (!empty($_POST)) {
            $contents = $_POST['contents'];
            $debt_amount = $_POST['debt_amount'];
            $installments = $_POST['installments'];
            $repayment_date = $_POST['repayment_date'];

            list($childDataId, $maxlending) = $this->getChildDataInfo($user_id);

            // if ($debt_amount > $maxlending) {
            //     $_SESSION['debt_error'] = '※貸出金額が最大貸出金額を超えています';
            //     header('Location: debt.php');
            //     exit();
            // }

            $approval_flag = FALSE;
            $debt_day = date('Y-m-d');

            $statement = $this->db->prepare(
                "INSERT INTO debt (user_id, family_id, debt_day, debt_amount, repayment_date, installments, contents, approval_flag) ".
                "VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $statement->execute(array(
                $user_id,
                $family_id,
                $debt_day,
                $debt_amount,
                $repayment_date,
                $installments,
                $contents,
                $approval_flag,
            ));

            $_SESSION['debt'] = $debt_amount;

            // LINEBOTへの通知処理
            $line_ids = $this->getLineId($family_id); // ユーザーのLINE IDを取得するメソッドを呼び出す
            // 直近のINSERTで割り振られたdebt_idを取得
            $debt_id = $this->db->lastInsertId();
            foreach ($line_ids as $line_id) {
                if($line_id){
                    $result = $this->MessageGet($user_id,$contents,$debt_amount,$repayment_date,$installments);
                    $message = "お金の貸出申請が届いたよ\n".$result;

                    $this->sendLineNotification($line_id, $message); // LINEBOTに通知を送るメソッドを呼び出す
                } 
            }
            header('Location: debt.php');
            exit();

        }
    }


    private function getChildDataInfo($user_id) {
        $stmt = $this->db->prepare("SELECT child_data_id FROM child_data WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return [$result['child_data_id']];
    }

    
    private function getLineId($family_id) {
        // userテーブルからfamily_idが一緒で親のuser_idを取得
        $stmt = $this->db->prepare("SELECT user_id FROM user WHERE family_id = :family_id AND (role_id = 21 OR role_id = 22 OR role_id = 23 OR role_id = 24 OR role_id = 25 OR role_id = 26)");
        $stmt->bindParam(':family_id', $family_id);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $uids = [];
        foreach ($results as $result) {
            // LINEdatabaseからUIDを取得
            $stmt2 = $this->db->prepare("SELECT UID FROM LINEdatabase WHERE id = :user_id");
            $stmt2->bindParam(':user_id', $result['user_id']);
            $stmt2->execute();
            $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
            
            if(isset($result2['UID'])) {
                $uids[] = $result2['UID'];
            }
        }
        
        return $uids;
    }
    

    private function MessageGet($user_id,$contents,$debt_amount,$repayment_date,$installments) {

        $stmt2 = $this->db->prepare("SELECT first_name FROM user WHERE user_id = :user_id");
        $stmt2->bindParam(':user_id', $user_id);
        $stmt2->execute();
        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                

        return "内容:".$contents . "\n金額:" . $debt_amount."\n返済日:".$repayment_date."\n分割回数:".$installments."\n担当者:".$result2['first_name']; 
    }

    private function sendLineNotification($line_id, $message) {
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

}

?>