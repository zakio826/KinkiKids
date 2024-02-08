<?php
// ユーザ名を取得
$stmt = $db->prepare("SELECT first_name,last_name,role_id FROM user WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION["user_id"]);
$stmt->execute();
$usernames = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    /* ナビゲーションバーの高さ分だけmainをずらす */
    main {
        padding-top: 4rem;
    }
</style>

<nav class="position-absolute w-100" style="height: 4rem; background-color: lemonchiffon;">
    <div class="container h-100 px-4">
        <div class="row align-items-center justify-content-between h-100">
            <div class="col">
                <h3 class="row row-cols-2 g-0 justify-content-start">
                    <?php if ($usernames[0]["role_id"] > 30) : ?>
                        <span class="col-auto">おなまえ：</span>
                        <span class="col-auto">
                            <span class="mx-2">
                                <?php echo $usernames[0]["last_name"]." ".$usernames[0]["first_name"]; ?>
                            </span>
                            <span class="">さん</span>
                        </span>
                    <?php else : ?>
                        <span class="col-auto">ユーザー名：</span>
                        <span class="col-auto">
                            <span class="mx-2">
                                <?php echo $usernames[0]["last_name"]." ".$usernames[0]["first_name"]; ?>
                            </span>
                            <span class="">さん</span>
                        </span>
                    <?php endif; ?>
                </h3>
            </div>
            <div class="col-auto row gx-2 justify-content-end">
                <a class="z-1 col-auto" href="<?php echo $absolute_path; ?>src/app/accounts/family_add.php">
                    <img src="<?php echo $absolute_path; ?>static/assets/Cog.png" width="40" height="40">
                </a>
                <a class="z-1 col-auto" href="<?php echo $absolute_path; ?>src/app/accounts/logout.php">
                    <img src="<?php echo $absolute_path; ?>static/assets/Cog.png" width="40" height="40">
                </a>
            </div>
        </div>
    </div>
</nav>
