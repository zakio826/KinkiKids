
<?php

try {

    $stmt = $dbh->prepare('UPDATE help SET name = :name, message = :message WHERE id = :id');

    $stmt->execute(array(':name' => $_POST['name'], ':message' => $_POST['message'], ':id' => $_POST['id']));

    echo "情報を更新しました。";

} catch (Exception $e) {
          echo 'エラーが発生しました。:' . $e->getMessage();
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>更新完了</title>
  </head>
  <body>          
  <p>
      <a href="help_add.php">投稿一覧へ</a>
  </p> 
  </body>
</html>
