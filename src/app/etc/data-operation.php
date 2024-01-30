<!-- 操作完了コンテンツ -->
<?php if (isset($_GET["dataOperation"]) && ($_GET["dataOperation"] === "delete" || $_GET["dataOperation"] === "update" || $_GET["dataOperation"] === "error" || $_GET["dataOperation"] === "numberError")) : ?>
    <section class="p-section p-section__full-screen" id="doneOperateBox">
        <div class="p-message-box <?php echo ($_GET['dataOperation'] === 'error' || $_GET['dataOperation'] === 'numberError') ? 'line-red' : 'line-blue'; ?>">
            <p id="doneText">
                <?php
                if ($_GET["dataOperation"] === "error") :
                    echo "正しく処理されませんでした";
                elseif ($_GET["dataOperation"] === "delete") :
                    echo "削除しました";
                elseif ($_GET["dataOperation"] === "update") :
                    echo "更新しました";
                elseif ($_GET["dataOperation"] === "numberError") :
                    echo "負の金額は入力できません";
                endif;
                ?>
            </p>
            <button class="c-button <?php echo ($_GET['dataOperation'] === 'error') ? 'c-button--bg-darkred' : 'c-button--bg-blue'; ?>" onclick="onClickOkButton('')">OK</button>
        </div>
    </section>
<?php endif; ?>
<!-- //操作完了コンテンツ -->