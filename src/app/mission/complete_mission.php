<?php if (isset($_GET["mission_operator"]) && ($_GET["mission_operator"] === "complete" || $_GET["mission_operator"] === "cancel")) : ?>
    <section class="p-section p-section__full-screen" id="doneOperateBox">
        <div class="p-message-box <?php echo $_GET['mission_operator'] === 'cancel' ? 'line-red' : 'line-blue'; ?>">
            <p id="doneText">
                <?php
                if ($_GET["mission_operator"] === "complete") :
                    echo "ミッションを達成しました。";
                elseif ($_GET["mission_operator"] === "cancel") :
                    echo "キャンセルしました。";
                endif;
                ?>
            </p>
            <button class="c-button <?php echo ($_GET['mission_operator'] === 'complete') ? 'c-button--bg-blue' : 'c-button--bg-darkred'; ?>" onclick="onClickOkButton('')">OK</button>
        </div>
    </section>
<?php endif; ?>