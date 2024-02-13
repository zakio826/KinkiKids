<?php
// ユーザ名を取得
$stmt = $db->prepare("SELECT first_name,last_name,role_id FROM user WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION["user_id"]);
$stmt->execute();
$usernames = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$select = $_SESSION["select"];
// 背景色の設定
$backgroundColor = ($select === 'adult') ?  '#AADBFF':'lemonchiffon'; // 適切な条件を指定してください
?>



<style>
    @import url('https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic&display=swap'); /*フォントの特定 */

    /* 全体の部分 */
    body {
        font-family: 'Zen Maru Gothic', sans-serif; /* フォントを指定 */
    }

    .nav_bar_moji {
        font-size:18px;
    }

    /* ナビゲーションバーの高さ分だけmainをずらす */
    main {
        padding-top: 4rem;
    }
    .menu-btn {
        position: absolute;
        top: 5px;
        right: 10px;
        display: flex;
        height: 50px;
        width: 50px;
        justify-content: center;
        align-items: center;
        z-index: 90;
        background-color: <?php echo ($select === 'adult') ? '#89CDFF' : '#fff27e'; ?>;
        border: 3px solid <?php echo ($select === 'adult') ? '#4F40FF' : '#ffa500'; ?>;
        border-radius: 10px;
    }
    .menu-btn span,
    .menu-btn span:before,
    .menu-btn span:after {
        content: '';
        display: block;
        height: 3px;
        width: 25px;
        border-radius: 3px;
        background-color: <?php echo ($select === 'adult') ? '#4F40FF' : '#ffa500'; ?>;
        position: absolute;
    }
    .menu-btn span:before {
        bottom: 8px;
    }
    .menu-btn span:after {
        top: 8px;
    }
    #menu-btn-check:checked ~ .menu-btn span {
        background-color: rgba(255, 255, 255, 0);
    }
    #menu-btn-check:checked ~ .menu-btn span::before {
        bottom: 0;
        transform: rotate(45deg);
    }
    #menu-btn-check:checked ~ .menu-btn span::after {
        top: 0;
        transform: rotate(-45deg);
    }
    #menu-btn-check {
        display: none;
    }
    #menu-btn-check:checked ~ .menu-content {
        left: 0;/*メニューを画面内へ*/
    }

    /* ナビゲーションバーの全体部分 */
    .menu-content{
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0;
        left: 100%;/*leftの値を変更してメニューを画面外へ*/
        z-index: 80;
        background-color: <?php echo ($select === 'adult') ? '#AADBFF' : '#fff27e'; ?>;
        transition: all 0.5s;/*アニメーション設定*/
    }

    .menu-content ul {
        padding: 70px 10px 0;
    }
    .menu-content ul li {
        border-bottom: solid 1px #000000;
        list-style: none;
    }
</style>

<nav class="position-absolute w-100 nav_bar_moji" style="height: 4rem; background-color: <?php echo $backgroundColor; ?>">
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
                    
                    <div class="hamburger-menu">
                        <input type="checkbox" id="menu-btn-check">
                        <label for="menu-btn-check" class="menu-btn"><span></span>
                        </label>
                        <div class="menu-content">
                            <ul>
                                <li>
                                    <?php if ($_SESSION["admin_flag"] == 1) : ?>
                                    <a class="z-1 col-auto" href="<?php echo $absolute_path; ?>src/app/accounts/family_add.php">
                                        家族アカウント追加
                                    </a>
                                </li>
                                <br>
                                <br>
                                <li>
                                    <?php endif; ?>
                                    <a class="z-1 col-auto" href="<?php echo $absolute_path; ?>src/app/accounts/logout.php">
                                    ログアウト
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </h3>
            </div>


        </div>
    </div>
</nav>
