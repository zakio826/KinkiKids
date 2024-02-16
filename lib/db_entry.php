<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 入力したデータを取得
    $user_id = $_SESSION['user_id'];
    $family_id = $_SESSION['family_id'];

    if(!empty($_POST)){

        if ($_POST['type'] == 0) {
            if(empty($_POST['spending_category'])){
                $_SESSION['spending_category_error'] = '*支出カテゴリーを選択してください。';
            }

            if(empty($_POST['payment_method'])){
                $_SESSION['payment_error'] = '*支払方法を選択してください。';
            }  
        }else{
            if(empty($_POST['income_category'])){
                $_SESSION['income_category_error'] = '*収入カテゴリーを選択してください。';
            }

        }

        if(empty($_POST['title'])){
            $_SESSION['title_error'] = '*タイトルを入力してください。';
        }

        if(empty($_POST['amount'])){
            $_SESSION['amount_error'] = '*金額を入力してください。';
        }

        if(empty($_POST['date'])){
            $_SESSION['date_error'] = '*日付を入力してください。';
        }

        if(isset($_SESSION['spending_category_error']) || isset($_SESSION['payment_error']) || isset($_SESSION['income_category_error']) || isset($_SESSION['title_error']) || isset($_SESSION['amount_error']) || isset($_SESSION['date_error'])){
            header('Location: spending_input.php');
            exit();
        }
        

        $income_expense_category_id = ($_POST['type'] == 0) ? $_POST['spending_category'] : $_POST['income_category'];
        $payment_id = ($_POST['type'] == 0) ? $_POST['payment_method'] : 9; // 収入の場合は支払方法が「なし」の(9)が選択される
        $income_expense_name = $_POST['title'];
        $income_expense_amount = $_POST['amount'];
        $income_expense_date = $_POST['date'];
        $income_expense_flag = ($_POST['type'] == 0) ? 1 : 0; // 支出の場合は1、収入の場合は0 (Flag判定)
    
        $stmt = $db->prepare('INSERT INTO income_expense (user_id, family_id, income_expense_category_id, payment_id, income_expense_name, income_expense_amount, income_expense_date, income_expense_flag) VALUES (:user_id, :family_id, :income_expense_category_id, :payment_id, :income_expense_name, :income_expense_amount, :income_expense_date, :income_expense_flag)');
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':family_id', $family_id, PDO::PARAM_INT);
        $stmt->bindParam(':income_expense_category_id', $income_expense_category_id, PDO::PARAM_INT);
        $stmt->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
        $stmt->bindParam(':income_expense_name', $income_expense_name, PDO::PARAM_STR);
        $stmt->bindParam(':income_expense_amount', $income_expense_amount, PDO::PARAM_INT);
        $stmt->bindParam(':income_expense_date', $income_expense_date, PDO::PARAM_STR);
        $stmt->bindParam(':income_expense_flag', $income_expense_flag, PDO::PARAM_INT);

    }


    //デバッグ用でエラー文の出力(!!!!最終的には外す!!!!)
    if ($stmt->execute()) {
        echo "データが正常に登録されました。";
    } else {
        echo "データの登録中にエラーが発生しました。";
    }
}
?>
