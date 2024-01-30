<!-- スマホ用切り替えタブ -->
<?php if (!isset($_GET["detail"])) : ?>
    <ul class="p-switch-tab" id="tab">

        <!-- ホームアイコン -->
        <li class="p-switch-tab__item is-active" id="homeTab">
            <!-- クリック時にJavaScriptでページ遷移 -->
            <div onclick="navigateTo('../../index.php')">
                <!-- <i class="fa-solid fa-house"></i> -->
                <img src="./img/house.png">
                <p>ホーム</p>
            </div>
        </li>

        <!-- 銀行アイコン -->
        <li class="p-switch-tab__item">
            <!-- <i class="fa-solid fa-coins"></i> -->
            <img src="./img/Coin.png">
            
            <!-- 親ユーザーか子どもユーザーかで漢字表記が変更される -->
            <p><?php echo $select === "adult" ? "銀行" : "ぎんこう" ?></p>
        </li>

        <!-- ふりかえりアイコン -->
        <li class="p-switch-tab__item">
            <!-- <i class="fa-solid fa-clipboard-list"></i> -->
            <img src="./img/Calendar.png">

            <!-- 親ユーザーか子どもユーザーかで漢字表記が変更される -->
            <p><?php echo $select === "adult" ? "振り返り" : "ふりかえり" ?></p>
        </li>

        <!-- 設定アイコン -->
        <li class="p-switch-tab__item">
            <!-- <i class="fa-solid fa-book"></i> -->
            <img src="./img/Cog.png">
            <p>設定</p>
        </li>

    </ul>
    <script>
        // JavaScriptでページ遷移する関数
        function navigateTo(page) {
            window.location.href = page;
        }
    </script>

<?php endif; ?>