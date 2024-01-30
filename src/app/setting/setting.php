<!-- 設定画面 -->
<div class="setting">
        <!-- <div class="c-layer"></div> -->

        <ul class="setting__overview" id="">
            <!-- <li>
                <a href="./index.php">
                    <i class="fa-solid fa-house"></i>ホーム画面
                </a>
            </li> -->
            <li>
                <a href="./account.php">
                    <i class="fa-solid fa-user"></i><?php echo $select === "adult" ? "ユーザー情報" : "ユーザーじょうほう" ?></a>
            </li>
            <li>
                <a href="./item-edit.php?editItem=1">
                    <i class="fa-solid fa-pen"></i><?php echo $select === "adult" ? "選択項目の編集" : "こうもくのへんしゅう" ?></a>
            </li>

            <!-- <li>
                <a href="./item-report.php"><i class="fa-solid fa-chart-simple"></i>項目別レポート</a>
            </li> -->


            <li>
                <a href="./amount-report.php"><i class="fa-solid fa-chart-simple"></i>レポート</a>
            </li>

            <li>
                <a href="./goal-setting.php"><i class="fa-solid fa-flag"></i><?php echo $select === "adult" ? "目標設定" : "もくひょうせってい" ?></a>
            </li>
            
            <!-- 親ユーザーの場合表示 -->
            <?php if ($select === "adult") : ?>
            <li>
                <a href="./review-setting.php"><i class="fa-solid fa-cog"></i>振り返り日設定</a>
            </li>
            <li>
                <a href="./interest-setting.php"><i class="fa-solid fa-cog"></i>利子設定</a>
            </li>
            <?php endif; ?>
            <li>
                <a href="./logout.php" id="logoutButton" onclick="logoutConfirm();">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>ログアウト
                </a>
            </li>
        </ul>
    </div>