<?php
$page_title = "パスワード再設定URLメール送信完了";
$page_headertitle = "金記キッズ";
include_once("./header.php");
?>
<main class="l-main">
    <section class="p-section p-section__request-success">
        <p>パスワード再設定URLメール送信完了</p>
        <p class="caution">第三者への情報漏えい防止のため、正しくない情報の場合も本画面が表示されます。<br>受信フォルダをご確認の上、メールが届かない場合はお手数ですが、再度<a href="./index.php" class="c-link">こちら</a>からお試しください。</p>
        <a href="../login.php" class="c-button c-button--bg-blue">ログイン画面に戻る</a>
    </section>
</main>

<?php
$footer_back = "off";
include_once("../component/common/footer.php");
?>
</body>

</html>