<section class="p-section p-section__bank js-switch-content fade-in hide" data-tab="tab-3">
    <img src="./img/bank.png" class="img_ginkou1">

    <!-- 子供ユーザーの場合 -->
    <?php if ($select === "child") : ?>

        <!-- 貸出機能画面 -->
        <div class="debt" onclick="location.href='./bank/lending.php'">
            <!-- <h3>貸出申請</h3> -->
            <img src="./img/func.png">
        </div>
    <?php endif; ?>

    <!-- 貸出ノート画面 -->
    <div class="debt debt_book" onclick="location.href='./bank/lending_list.php'">
        <img src="./img/note.png">
        <!-- <h3>貸出帳</h3> -->
    </div>
    <style>
        .img_ginkou1 {
            max-width: 100%;
        }
    </style>
</section>