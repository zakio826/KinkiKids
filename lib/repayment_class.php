<?php
class repayment {
    private $db;

    function __construct($db, $user_id, $family_id) {
        $this->db = $db;
        $this->error = [];

        if (isset($_POST["consent_repayment"])){//ポイント追加処理
            $debtid = $_POST["consent_repayment"];

            $debt_info = $this->getDebtInfo($debtid);


            $updated_amount = $debt_info['repayment_amount'] - $debt_info['repayment_installments'];

            $stmt = $this->db->prepare("UPDATE debt SET repayment_amount = :updated_amount WHERE debt_id = :debt_id");
            $stmt->bindParam(':debt_id', $debtid, PDO::PARAM_INT);
            $stmt->bindParam(':updated_amount', $updated_amount, PDO::PARAM_STR);
            $stmt->execute();

            // income_expenseに新しいレコードを挿入
            $stmt = $this->db->prepare("INSERT INTO income_expense (user_id, family_id, income_expense_category_id, payment_id, income_expense_name, income_expense_detail, income_expense_amount, income_expense_date, income_expense_flag)
            VALUES (:user_id, :family_id, 14, 2, 'へんさい', :detail, :amount, :date, 1)");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':family_id', $family_id, PDO::PARAM_INT);
            $stmt->bindParam(':detail', $debt_info['contents'], PDO::PARAM_STR);
            $stmt->bindParam(':amount', $debt_info['repayment_installments'], PDO::PARAM_STR);
            $stmt->bindParam(':date', date('Y-m-d'), PDO::PARAM_STR);
            $stmt->execute();

            $child_data_id = $this->getChildDataId($user_id);

            $stmt = $this->db->prepare("UPDATE child_data SET savings = savings - :amount WHERE child_data_id = :child_data_id");
            $stmt->bindParam(':amount', $debt_info['repayment_installments'], PDO::PARAM_STR);
            $stmt->bindParam(':child_data_id', $child_data_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($updated_amount <= 0) {
                $stmt = $this->db->prepare("DELETE FROM debt WHERE debt_id = :debt_id");
                $stmt->bindParam(':debt_id', $debtid, PDO::PARAM_INT);
                $stmt->execute();
            }

            $_SESSION['updated'] = true;
            header('Location: ../index.php');
            exit();
        }
    }

    public function getDebtInfo($debt_id) {
        $query = "SELECT * FROM debt WHERE debt_id = :debt_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':debt_id', $debt_id, PDO::PARAM_INT);
        $stmt->execute();
        $debt_info = $stmt->fetch(PDO::FETCH_ASSOC);

        return $debt_info;
    }

    private function getChildDataId($user_id) {
        $stmt = $this->db->prepare("SELECT child_data_id FROM child_data WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $child_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($child_data) {
            return $child_data['child_data_id'];
        }

        return null;
    }
}

?>