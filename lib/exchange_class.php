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
            
            $_SESSION['points_success'] = true;
            $_SESSION['points_count'] = $points;

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
