<?php
// test
class testpoint{
    private $error; // エラー情報を保持するプロパティ
    private $db; // データベース接続を保持するプロパティ

    function __construct($db){
        $this->db = $db;
        $this->error = []; // 初期化
    }
    public function role_select(){
        // $this->db が null でないことを確認
        if ($this->db !== null) { 
            $sql = "SELECT sender_id,receiver_id,messagetext,sent_time FROM line_message WHERE receiver_id = ";
            $id = 8;
            $sql = $sql.$id;
            $stmt = $this->db->query($sql);
            foreach($stmt as $record){
                echo "メッセージ:";
                echo $record[2];
                echo "<br>";
                echo "送信日:";
                echo $record[3];
                echo "<br><br>";
            }
        }else{
            echo "<p>エラー</p>";
        }
    }
    public function sessiontest(){
        if(isset($_SESSION["user_id"])){
            echo "ログインしております";
        }else{
            echo "ログインしてや";
        }
    }
}

?>