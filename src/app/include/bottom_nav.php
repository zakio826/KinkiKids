<?php
$select = $_SESSION["select"];
?>

<style>
nav {
    max-width: 100vw;
    width: 100%;
    text-align: center;
}

ul {
    list-style: none;
    padding: 0;
    margin: 0;
    width: 100%;
}

/* ↓ 横幅小さくてもバーが改行されないやつ ↓ */
@media (max-width: 374px) {
    ul {
    list-style: none;
    padding: 0;
    margin: 0;
    width: 100%;
    display: flex;
    justify-content: flex-start;
    align-items: flex-end;
    scrollbar-width: auto;
    overflow-y: auto; /* 横にスクロールできるよ */
    }
}



/* 下のメニュー配置 */
nav.bottom_nav_bar1 {
    text-align: center;
    position: fixed;
    width: 100%;
    bottom: 0px;
    font-size: 0;
    z-index: 99;
}

/* 下のメニュー背景 */
.bottom_nav_bar1 {
    background-color: <?php echo ($select === 'adult') ? '#AADBFF' : '#fff27e'; ?>;;
    border-top: 4px solid #8fc31f;
    padding-bottom: 15px;
}

/* アイコンたちの上余白 */
.bottom_nav_bar4 {
    margin-top: 10px;
}

/* リストのスタイル変更 */
li.bottom_nav_bar4 {
    display: inline-block;
    margin-left: 10px;
    margin-right: 10px;
}

/* ホーム以外のアイコンスタイル */
.bottom_nav_img1 {
    height: 50px;
    /* margin-bottom: -10px; */
}

/* ホームのアイコンスタイル） */
.bottom_nav_img2 {
    height: 55px;
    /* margin-bottom: -10px; */
}

/* アイコン下の文字スタイル */
.bottom_nav_font1 {
    font-weight: bold;
    font-size: 10px;
}

li {
    display: inline-block;
    margin-right: 20px;
}

a {
    text-decoration: none;
    color: #333;
    font-weight: bold;
    font-size: 16px;
}

a:hover {
    color: #0066cc;
}

/* --------------------------------------------------------------- */
/* ホームの位置にでっぱりを付けようとしたけど無理だったやつ */
/* .bottom_nav_bar2 {
    background-color: #fff27e;
    border-radius: 50%;
    width: 100px;
    height: 68px;
    margin: auto;
    margin-bottom: -31px;
    margin-top: -42px;
    border: 4px solid #8fc31f;
}

.bottom_nav_bar3 {
    background-color: #fff27e;
    margin-top: -89px;
    height: 93px;
} */
/* --------------------------------------------------------------- */


/* 真ん中のボタンの画像を大きくするCSS */
/* li:nth-child(3) a img {
        width: 30px;
        height: 30px;
} */
</style>



<!-- --------------------------------------------------------------- -->
<!-- 大幅変更前のやつ -->

<!-- <nav>
    <ul>
        <li><a href="<?php echo $absolute_path; ?>src/app/record/calendar.php"><img src="">カレンダー</a></li>
        <li><a href="<?php echo $absolute_path; ?>src/app/point/help_add.php"><img src="">お手伝い</a></li>
        <li><a href="<?php echo $absolute_path; ?>src/app/spending/spending_input.php"><img src="">収支登録</a></li>
        <li><a href="<?php echo $absolute_path; ?>src/app/goal/goal.php"><img src="">目標</a></li>
        <li><a href=""><img src="">設定</a></li>
    </ul>
</nav> -->

<!-- --------------------------------------------------------------- -->



<!-- --------------------------------------------------------------- -->
<!-- 大幅変更後のやつ -->

<nav class="bottom_nav_bar1">
    <ul>
        <!-- <div class="bottom_nav_bar2">
        </div> -->

        <li class="bottom_nav_bar4">
            <a href="<?php echo $absolute_path; ?>src/app/record/calendar.php">
            <img src="<?php echo $absolute_path; ?>static/assets/calendar_yellow.png" class="bottom_nav_img1">
            </a>
            <br><b class="bottom_nav_font1">カレンダー</b>
        </li>
        <li class="bottom_nav_bar4">
            <a href="<?php echo $absolute_path; ?>src/app/point/help_add.php">
            <img src="<?php echo $absolute_path; ?>static/assets/help_mission.png" alt="お手伝い" class="bottom_nav_img1">
            </a>
            <br><b class="bottom_nav_font1">おてつだい</b>
        </li>
        <li class="bottom_nav_bar4">
            <a href="<?php echo $absolute_path; ?>src/app/index.php">
                <img src="<?php echo $absolute_path; ?>static/assets/homeB.png" alt="ホーム" class="bottom_nav_img2">
            </a>
            <br><b class="bottom_nav_font1">ホーム</b>
        </li>
        <li class="bottom_nav_bar4">
            <a href="<?php echo $absolute_path; ?>src/app/money/debt.php">
            <img src="<?php echo $absolute_path; ?>static/assets/bank_icon.png" alt="ぎんこう" class="bottom_nav_img1">
            </a>
            <br><b class="bottom_nav_font1">ぎんこう</b>
        </li>
        <li class="bottom_nav_bar4">
            <a href="<?php echo $absolute_path; ?>src/app/spending/spending_input.php">
            <img src="<?php echo $absolute_path; ?>static/assets/okodukaityouA.png" alt="お小遣い帳" class="bottom_nav_img1">
            </a>
            <br><b class="bottom_nav_font1">おこづかい帳</b>
        </li>
        
        <!-- <div class="bottom_nav_bar3">
        </div> -->
    </ul>
        
</nav>

<!-- --------------------------------------------------------------- -->