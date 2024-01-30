<!-- 収支データ入力 -->
<div id="input_data" class="household-input switch-household" data-switch="switch-1">
    <form class="p-form p-form--input-record" name="recordInput" action="./record-create.php" method="POST">
        <input type="hidden" id="select" value="<?php echo $select; ?>">

        <div class="category">
            <input type="hidden" name="input_time" id="input_time" value="<?php echo date("Y/m/d-H:i:s"); ?>">
            <div class="p-form__flex-input">
                <p>日付</p>
                <div class="p-form--input-record__dateinput u-flex-box">
                    <span onclick="onChangeInputDate('past');">＜</span>
                    <input type="date" name="date" id="date" value="<?php echo (isset($r_date) ? $r_date : date("Y-m-d")); ?>" required>
                    <span onclick="onChangeInputDate('future');">＞</span>
                </div>
            </div>

            <div class="p-form__flex-input">
                <p>うちわけ</p>
                <input type="text" name="title" id="title" maxlength="15" value="<?php echo $r_title; ?>" required>
            </div>

            <?php if ($select === "adult") { ?>
                <?php
                $sql = "SELECT COUNT(title), title FROM records WHERE user_id = ? GROUP BY title ORDER BY COUNT(title) DESC, input_time DESC LIMIT 3";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("i", $user["id"]);
                $stmt->execute();
                $stmt->store_result();
                $count = $stmt->num_rows();
                $stmt->bind_result($count, $title);
                ?>
            <?php } else if ($select === "child") { ?>
                <?php
                $sql = "SELECT COUNT(title), title FROM records WHERE child_id =? GROUP BY title ORDER BY COUNT(title) DESC, input_time DESC LIMIT 3";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("i", $user["id"]);
                $stmt->execute();
                $stmt->store_result();
                $count = $stmt->num_rows();
                $stmt->bind_result($count, $title);
                ?>
                <!--↑ここまで-->
            <?php } ?>

            <?php if ($count > 0) : ?>
                <div class="p-form__flex-input p-form__often-use-title">
                    <p>よく使う<br>タイトル</p>
                    <ul class="u-flex-box">
                        <?php while ($stmt->fetch()) : ?>
                            <li onclick="onChangeTitle('<?php echo h($title); ?>')"><?php echo h($title); ?></li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($select === "adult") : ?>
                <div class="p-form__flex-input type">
                    <input id="spending" type="radio" name="type" value="0" onchange="onRadioChangeType(0);" <?php echo (isset($_SESSION["r_type"]) && $r_type == 0) ? "checked" : ""; ?> required>
                    <label for="spending"><?php echo $select === "adult" ? "支出" : "つかった" ?></label>
                    <input type="radio" name="type" id="income" value="1" onchange="onRadioChangeType(1);" <?php echo (isset($_SESSION["r_type"]) && $r_type == 1) ? "checked" : ""; ?>>
                    <label for="income"><?php echo $select === "adult" ? "収入" : "もらった" ?></label>
                </div>
            <?php endif; ?>

            <div class="p-form__flex-input">
                <p>金額</p>
                <input type="number" name="amount" id="amount" step="1" maxlength="7" pattern="^[0-9]+$" value="<?php echo $r_amount; ?>" required>
            </div>

            <?php if ($select === "adult") { ?>
                <div class="u-js__show-switch flex p-form__flex-input sp-change-order" id="spendingCategoryBox">
                    <p class="long-name">支出カテゴリー</p>
                    <select name="spending_category" id="spendingCategory">
                        <option value="0">選択してください</option>
                        <?php
                        $stmt_spendingcat = $db->prepare("SELECT id, name FROM spending_category WHERE family_id = ? GROUP BY name");
                        $stmt_spendingcat->bind_param("i", $family_id);
                        sql_check($stmt_spendingcat, $db);
                        $stmt_spendingcat->bind_result($id, $name);
                        while ($stmt_spendingcat->fetch()) :
                        ?>
                            <option value="<?php echo h($id); ?>" <?php echo $r_spendingCat == $id ? "selected" : ""; ?>><?php echo h($name) ?></option>
                        <?php endwhile; ?>
                    </select>
                    <a id="spendingCatEdit" class="c-button c-button--bg-gray" href="./item-edit.php?editItem=0" onclick="onClickCatEdit('spendingCatEdit');">編集 追加</a>
                </div>

                <div class="u-js__show-switch flex p-form__flex-input sp-change-order" id="incomeCategoryBox">
                    <p class="long-name">収入カテゴリー</p>
                    <select name="income_category" id="incomeCategory">
                        <option value="0">選択してください</option>
                        <?php
                        $stmt_incomecat = $db->prepare("SELECT id, name FROM income_category WHERE family_id = ?");
                        $stmt_incomecat->bind_param("i", $family_id);
                        sql_check($stmt_incomecat, $db);
                        $stmt_incomecat->bind_result($id, $name);
                        while ($stmt_incomecat->fetch()) :
                        ?>
                            <option value="<?php echo h($id); ?>"><?php echo h($name) ?></option>
                        <?php endwhile; ?>
                    </select>
                    <a class="c-button c-button--bg-gray" href="./item-edit.php?editItem=1">編集 追加</a>
                </div>

                <div id="paymentMethodBox" class="u-js__show-switch flex p-form__flex-input sp-change-order">
                    <p class="long-name">支払い方法</p>
                    <select name="payment_method" id="paymentMethod" onchange="hasChildSelect('2', creditSelectBox, qrChecked);hasChildSelect('3', qrSelectBox, creditChecked);">
                        <option value="0">選択してください</option>
                        <?php
                        $fixedPaymentMethod = ["現金", "クレジット", "スマホ決済"];
                        $fixedPaymentMethod_id = ["", "radioCredit", "radioQr"];
                        for ($i = 0; $i < 3; $i++) : ?>
                            <option value="<?php echo $i + 1; ?>" id="<?php echo $fixedPaymentMethod_id[$i]; ?>" <?php echo $r_paymentMethod == $i + 1 ? "selected" : ""; ?>>
                                <?php echo $fixedPaymentMethod[$i]; ?>
                            </option>
                        <?php endfor; ?>

                        <?php
                        $stmt_paymethod = $db->prepare("SELECT id, name FROM payment_method WHERE id > 3 AND family_id = ?");
                        $stmt_paymethod->bind_param("i", $family_id);
                        sql_check($stmt_paymethod, $db);
                        $stmt_paymethod->bind_result($id, $name);
                        while ($stmt_paymethod->fetch()) :
                        ?>
                            <option value="<?php echo h($id); ?>" <?php echo $r_paymentMethod == $id ? "selected" : ""; ?>><?php echo h($name); ?></option>
                        <?php endwhile; ?>
                    </select>
                    <a id="paymentMethodEdit" class="c-button c-button--bg-gray" href="./item-edit.php?editItem=2" onclick="onClickCatEdit('paymentMethodEdit');">編集 追加</a>
                </div>

                <div class="u-js__show-switch flex p-form__flex-input sp-change-order" id="creditSelectBox">
                    <p class="long-name">クレジットカード</p>
                    <div class="p-form__item-box">
                        <select name="credit">
                            <option value="0">選択しない</option>
                            <?php
                            $stmt_credit = $db->prepare("SELECT id, name FROM creditcard WHERE family_id = ?");
                            $stmt_credit->bind_param("i", $family_id);
                            sql_check($stmt_credit, $db);
                            $stmt_credit->bind_result($id, $name);
                            while ($stmt_credit->fetch()) :
                            ?>
                                <option value="<?php echo h($id); ?>"><?php echo h($name) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <a id="creditEdit" class="c-button c-button--bg-gray" href="./item-edit.php?editItem=3" onclick="onClickCatEdit('creditEdit');">編集 追加</a>
                </div>

                <div class="u-js__show-switch flex p-form__flex-input sp-change-order" id="qrSelectBox">
                    <p class="long-name">スマホ決済種類</p>
                    <div class="p-form__item-box">
                        <select name="qr">
                            <option value="0">選択しない</option>
                            <?php
                            $stmt_qr = $db->prepare("SELECT id, name FROM qr WHERE family_id = ?");
                            $stmt_qr->bind_param("i", $family_id);
                            sql_check($stmt_qr, $db);
                            $stmt_qr->bind_result($id, $name);
                            while ($stmt_qr->fetch()) :
                            ?>
                                <option value="<?php echo h($id); ?>"><?php echo h($name) ?></option>
                            <?php endwhile; ?>
                        </select>

                    </div>
                    <a id="qrEdit" class="c-button c-button--bg-gray" href="./item-edit.php?editItem=4" onclick="onClickCatEdit('qrEdit');">編集 追加</a>
                </div>
            <?php } ?>

            <?php if ($select === "child") { ?>
                <div class="u-js__show-switch flex p-form__flex-input sp-change-order show" id="spendingCategoryBox">
                    <p class="long-name">支出カテゴリー</p>
                    <select name="spending_category" id="spendingCategory">
                        <option value="0">選択してください</option>
                        <?php
                        $stmt_spendingcat = $db->prepare("SELECT id, name FROM spending_category WHERE family_id = ?");
                        $stmt_spendingcat->bind_param("i", $family_id);
                        sql_check($stmt_spendingcat, $db);
                        $stmt_spendingcat->bind_result($id, $name);
                        while ($stmt_spendingcat->fetch()) :
                        ?>
                            <option value="<?php echo h($id); ?>" <?php echo $r_spendingCat == $id ? "selected" : ""; ?>><?php echo h($name) ?></option>
                        <?php endwhile; ?>
                    </select>
                    <a id="spendingCatEdit" class="c-button c-button--bg-gray" href="./item-edit.php?editItem=0" onclick="onClickCatEdit('spendingCatEdit');">編集 追加</a>
                </div>

                <div class="u-js__show-switch flex p-form__flex-input sp-change-order" id="incomeCategoryBox">
                    <p class="long-name">収入カテゴリー</p>
                    <select name="income_category" id="incomeCategory">
                        <option value="0">選択してください</option>
                        <?php
                        $stmt_incomecat = $db->prepare("SELECT id, name FROM income_category WHERE family_id = ?");
                        $stmt_incomecat->bind_param("i", $family_id);
                        sql_check($stmt_incomecat, $db);
                        $stmt_incomecat->bind_result($id, $name);
                        while ($stmt_incomecat->fetch()) :
                        ?>
                            <option value="<?php echo h($id); ?>"><?php echo h($name) ?></option>
                        <?php endwhile; ?>
                    </select>
                    <a class="c-button c-button--bg-gray" href="./item-edit.php?editItem=1">編集 追加</a>
                </div>

                <input type="hidden" name="type" value="0">
                <input type="hidden" name="payment_method" value="0">
                <input type="hidden" name="credit" value="0">
                <input type="hidden" name="qr" value="0">
            <?php } ?>

            <div>
                <textarea name="memo" id="" cols="45" rows="5" placeholder="入力収支の詳細"></textarea>
            </div>

            <input class="c-button c-button--bg-blue" type="submit" name="record_create" value="登録">
        </div>
    </form>

    <form action="" method="POST">
        <input type="submit" class="c-button c-button--bg-gray reset" name="record_reset" value="リセット">
    </form>
</div>