<?php
// ホームページ画面PHP

// セッション変数 $_SESSION["loggedin"]を確認。未ログインだったらログインページへリダイレクト
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: ./accounts/login.php");
    exit;
}

$stmt = $db->prepare("SELECT * FROM user WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION["user_id"]);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<nav class="position-absolute w-100" style="background-color: lemonchiffon;">
    <div class="container px-3 py-2">
        <div class="row align-items-center justify-content-between">
            <div class="col">
                <?php if ($users[0]["role_id"] > 30) : ?>
                    <h3 class="d-inline">
                        おなまえ：<span class="px-2"><?php echo $users[0]["last_name"]." ".$users[0]["first_name"]; ?></span>さん
                    </h3>
                <?php else : ?>
                    <h3 class="row row-cols-3 justify-content-start">
                        <span class="col-auto">ユーザー名：</span>
                        <span class="col-auto"><?php echo $users[0]["last_name"]." ".$users[0]["first_name"]; ?></span>
                        <span class="col-auto">さん</span>
                    </h3>
                <?php endif; ?>
            </div>
            <div class="col-auto"><img src="<?php echo $absolute_path; ?>static/assets/Cog.png" width="40" height="40" data-tab="3"></div>
        </div>
    </div>
</nav>
