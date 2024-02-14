<?php
class repayment {
    private $db;

    function __construct($db, $user_id, $family_id) {
        $this->db = $db;
        $this->error = [];

        if (isset($_POST["consent_repayment"])){//ポイント追加処理
            $debtid = $_POST["consent_repayment"];

            $debt_info = $this->getDebtInfo($debtid);
            $debt_id = isset($_GET['debt_id']) ? $_GET['debt_id'] : null;


            $updated_amount = $debt_info['repayment_amount'] - $debt_info['repayment_installments'];

            // ハイフンを除いた日付の取得
            $post_date = str_replace('-', '', $debt_info['repayment_date']);

            $next_schedule = $this->next_schedule_monthly($post_date);

            // 結果の表示
            $nextrepaymentday = date('Y-m-d', $next_schedule['utc_jp']);

            $stmt = $this->db->prepare("UPDATE debt SET repayment_amount = :updated_amount, repayment_date = :repayment_date WHERE debt_id = :debt_id");
            $stmt->bindParam(':debt_id', $debtid, PDO::PARAM_INT);
            $stmt->bindParam(':updated_amount', $updated_amount, PDO::PARAM_STR);
            $stmt->bindParam(':repayment_date', $nextrepaymentday, PDO::PARAM_STR);
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

    private function next_schedule_monthly($post_date) {
        // 01234567
        // YYYYMMDD
        $datetime['year']   = (int) substr($post_date, 0, 4);
        $datetime['month']  = (int) substr($post_date, 4, 2);
        $datetime['day']    = (int) substr($post_date, 6, 2);
        // 固定の時刻：00:00:00
        $datetime['second'] = 0;
        $datetime['minute'] = 0;
        $datetime['hour']   = 0;

        $check_month = $datetime['month'];

        $check_flag = true;

        for ($i = 1; $i < 13; $i++) {
            // 翌月作成
            $check_month++;

            // 12月以内に変更する
            if ($check_month >= 13) {
                $check_month = $check_month - 12;

                if ($check_flag) {
                    $datetime['year'] = $datetime['year'] + 1;
                    $check_flag        = false;
                }
            }

            // 正しい日付かをチェックする
            if (checkdate($check_month, $datetime['day'], $datetime['year'])) {
                break;
            }
        }

        // UTC+9とUTCに変換
        $schedule_time['utc_jp'] = mktime($datetime['hour'], $datetime['minute'], $datetime['second'], $check_month, $datetime['day'], $datetime['year']);
        // $schedule_time['utc']    = $schedule_time['utc_jp'] - 32400;

        return $schedule_time;
    }
}

?>