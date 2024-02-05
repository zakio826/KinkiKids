
<?php
require("../../../lib/help_class.php");

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>編集</title>

    <div class="contact-form">
        <h2>編集</h2>
        <form action="help_edit_process.php" method="post">
            <p>
                <label>お手伝い名：</label>
                <input type="text" name="help_name" value="<?php if (!empty($result['help_name'])) echo(htmlspecialchars($result['help_name'], ENT_QUOTES, 'UTF-8'));?>">
            </p>
            <p>
                <label>お手伝い詳細：</label>
                <input type="text" name="help_detail" value="<?php if (!empty($result['help_detail'])) echo(htmlspecialchars($result['help_detail'], ENT_QUOTES, 'UTF-8'));?>">
            </p>
            <p>
                <label>獲得ポイント：</label>
                <input type="number" name="get_point" value="<?php if (!empty($result['get_point'])) echo(htmlspecialchars($result['get_point'], ENT_QUOTES, 'UTF-8'));?>">
            </p>

            <input type="submit" value="編集する">

        </form>
    </div>
        <a href="help_add.php">投稿一覧へ</a>
</body>
</html>
