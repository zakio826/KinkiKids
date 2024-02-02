<?php
// ユーザ名を取得
$stmt = $db->prepare("SELECT first_name,last_name,role_id FROM user WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION["user_id"]);
$stmt->execute();
$usernames = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<nav class="position-absolute w-100" style="height: 4rem; background-color: lemonchiffon;">
    <div class="container h-100 px-4">
        <div class="row align-items-center justify-content-between h-100">
            <div class="col">
                <?php if ($usernames[0]["role_id"] > 30) : ?>
                    <h3 class="d-inline">
                        おなまえ：<span class="px-2"><?php echo $usernames[0]["last_name"]." ".$usernames[0]["first_name"]; ?></span>さん
                    </h3>
                <?php else : ?>
                    <h3 class="row row-cols-3 justify-content-start">
                        <span class="col-auto">ユーザー名：</span>
                        <span class="col-auto"><?php echo $usernames[0]["last_name"]." ".$usernames[0]["first_name"]; ?></span>
                        <span class="col-auto">さん</span>
                    </h3>
                <?php endif; ?>
            </div>
            <div class="col-auto"><img src="<?php echo $absolute_path; ?>static/assets/Cog.png" width="40" height="40" data-tab="3"></div>
        </div>
    </div>
</nav>