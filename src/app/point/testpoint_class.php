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
            if(isset($_SESSION["id"])){
                $sql = "SELECT sender_id,receiver_id,messagetext,sent_time FROM line_message WHERE receiver_id = ";
                $id = $_SESSION["id"];
                $sql = $sql.$id;
                $stmt = $this->db->query($sql);
                $f = true;
                foreach($stmt as $record){
                    $f = false;
                    echo "メッセージ:";
                    echo $record[2];
                    echo "<br>";
                    echo "送信日:";
                    echo $record[3];
                    echo "<br><br>";
                }
                if ($f){
                    echo "<p>メッセージはありません</p>";
                }
            }else{
                echo "ログインせい";
            }
        }else{
            echo "<p>エラー</p>";
        }
    }
    public function sessiontest(){
        if(isset($_SESSION["id"])){
            echo "ログインID=";
            echo $_SESSION["id"];
        }else{
            echo "ログインしてや";
        }
    }
}

?>