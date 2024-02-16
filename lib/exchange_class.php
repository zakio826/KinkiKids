<?php
class Exchange {
    private $db;

    function __construct($db) {
        $this->db = $db;
        $this->error = [];

        if (!empty($_POST)) {
            $selectedUserId = $_POST['selected_user'];
            $points = $_POST['points'];

            list($childDataId, $savings, $havePoints) = $this->getChildDataInfo($selectedUserId);

            if (empty($selectedUserId)) {
                $_SESSION['child_error'] = '*子供を選択してください。';
                // header('Location: exchange.php');
                // exit();
            }

            if (empty($points)) {
                $_SESSION['point_error'] = '*交換するポイントを入力してください。';
                // header('Location: exchange.php');
                // exit();
            }
            
            if ($points > $havePoints) {
                $_SESSION['exchange_error'] = '*入力されたポイントが所持ポイントを超えています。';
                // header('Location: exchange.php');
                // exit();
            }

            if(isset($_SESSION['child_error']) || isset($_SESSION['point_error']) || isset($_SESSION['exchange_error'])){
                header('Location: exchange.php');
                exit();
            }

            $result_points = $havePoints - $points;
            $result_savings = $savings + $points;

            // child_data_idが指定されたIDと一致するレコードのhave_pointsとsavingsを更新
            $updateStatement = $this->db->prepare(
                "UPDATE child_data 
                SET have_points = :have_points, savings = :savings
                WHERE child_data_id = :child_data_id"
            );
    
            $updateStatement->bindValue(':have_points', $result_points, PDO::PARAM_INT);
            $updateStatement->bindValue(':savings', $result_savings, PDO::PARAM_INT);
            $updateStatement->bindValue(':child_data_id', $childDataId, PDO::PARAM_INT);
    
            $updateStatement->execute();

            $_SESSION['exchange_points'] = $points;
            header('Location: exchange.php');
            exit();

        }
    }

    private function getChildDataInfo($selectedUserId) {
        $stmt = $this->db->prepare("SELECT child_data_id, savings, have_points FROM child_data WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $selectedUserId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return [$result['child_data_id'], $result['savings'], $result['have_points']];
    }

    // フォームで子供ユーザーを表示
    public function getFamilyUser() {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $familyId = $result['family_id'];

        $stmt = $this->db->prepare("SELECT * FROM user WHERE family_id = :family_id AND NOT user_id = :user_id AND role_id > 30");
        $stmt->bindParam(':family_id', $familyId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $record) {
            echo '<option value="';
            echo $record['user_id'];
            echo '">';
            echo $record['first_name'];
            echo "</option>";
        }
    }
}
?>
