<?php
// test
class havepoint{
    private $error; // エラー情報を保持するプロパティ
    private $db; // データベース接続を保持するプロパティ

    function __construct($db){
        $this->db = $db;
        $this->error = []; // 初期化
    }
    public function display_point(){
        //$_SESSION["id"] = 10;
        // $this->db が null でないことを確認
        // $_SESSION["id"] = 6;
        // echo "<p>ID=".$_SESSION["id"]."</p>";
        // echo "<br>";

        if ($this->db !== null) { 
            if(isset($_SESSION["id"])){
                $sql = "SELECT have_points FROM child_data WHERE user_id = ";
                $id = $_SESSION["id"];
                $sql = $sql.$id;
                $stmt = $this->db->query($sql);
                $f = true;
                foreach($stmt as $record){
                    $f = false;
                    echo "現在のポイント:";
                    echo $record[0];
                    echo "<br><br>";
                }
                if ($f){
                    echo "<p>子供でログインせい</p>";
                }
            }else{
                echo "<p>ログインせい</p>";
            }
        }else{
            echo "<p>エラー</p>";
        }
    }
}

?>