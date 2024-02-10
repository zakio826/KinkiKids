<?php
class debt {
    private $db;

    function __construct($db) {
        $this->db = $db;
        $this->error = [];

        if (!empty($_POST)) {
            $contents = $_POST['contents'];
            $debt_amount = $_POST['debt_amount'];
            $installments = $_POST['installments'];
            $reason = $_POST['reason'];
            $repayment_date = $_POST['repayment_date'];

            $family_id = $this->getFamilyId($_SESSION["user_id"]);
            $approval_flag = FALSE;
            $interest = 1;
            $debt_day = date('Y-m-d');

            $statement = $this->db->prepare(
                "INSERT INTO debt (user_id, family_id, debt_day, debt_amount, repayment_date, installments, contents, reason, approval_flag, interest) ".
                "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $statement->execute(array(
                $_SESSION["user_id"],
                $family_id,
                $debt_day,
                $debt_amount,
                $repayment_date,
                $installments,
                $contents,
                $reason,
                $approval_flag,
                $interest
            ));

            $_SESSION['debt'] = $debt_amount;
            header('Location: debt.php');
            exit();

        }
    }
    
    private function getFamilyId($user_id) {
        $stmt = $this->db->prepare("SELECT family_id FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['family_id'];
    }
}

?>