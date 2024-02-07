<style>
nav {
    text-align: center;
}

ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
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



/* 真ん中のボタンの画像を大きくするCSS */
/* li:nth-child(3) a img {
        width: 30px;
        height: 30px;
} */
</style>

<nav>
    <ul>
        <li><a href="<?php echo $absolute_path; ?>src/app/record/calendar.php"><img src="">カレンダー</a></li>
        <li><a href="<?php echo $absolute_path; ?>src/app/point/help_add.php"><img src="">お手伝い</a></li>
        <li><a href="<?php echo $absolute_path; ?>src/app/spending/spending_input.php"><img src="">収支登録</a></li>
        <li><a href="<?php echo $absolute_path; ?>src/app/goal/goal.php"><img src="">目標</a></li>
        <li><a href=""><img src="">設定</a></li>
    </ul>
</nav>