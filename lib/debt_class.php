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
}

?>