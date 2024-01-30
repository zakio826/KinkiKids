<a href="#modal" class="p-banner c-button c-button--bg-blue js-detail-search">絞り込み検索</a>

<form method="POST" class="p-form--detail-search remodal" data-remodal-id="modal">
    <button data-remodal-action="close" class="remodal-close"></button>
    <div class="p-form__flex-input">
        <p>タイトルキーワード</p>
        <input type="text" name="filtering-title" maxlength="15" value="<?php echo h($filtering_title); ?>">
    </div>

    <?php if ($select === "adult") : ?>
        <div class="p-form__flex-input">
            <p>子どもの名前</p>
            <select name="filtering-child" id="">
                <option value="0">選択してください</option>
                <?php
                $sql = "SELECT id, name FROM child WHERE parent = ?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("i", $user["id"]);
                sql_check($stmt, $db);
                $stmt->bind_result($id, $name);
                while ($stmt->fetch()) :
                ?>
                    <option value="<?php echo h($id); ?>" <?php echo $filtering_child == $id ? "selected" : ""; ?>><?php echo h($name); ?></option>
                <?php endwhile; ?>
            </select>
            <i class="fa-solid fa-angle-down"></i>
        </div>
    <?php endif; ?>

    <?php if ($select === "child") : ?>
        <div class="p-form__flex-input">
            <p>もらった・つかった</p>
            <select name="filtering-spendingcat" id="">
                <option value="0">選択してください</option>
                <option value="1" <?php echo $filtering_spendingcat == $id ? "selected" : ""; ?>>もらった</option>
                <option value="2" <?php echo $filtering_spendingcat == $id ? "selected" : ""; ?>>つかった</option>
            </select>
            <i class="fa-solid fa-angle-down"></i>
        </div>
    <?php endif; ?>

    <div class="p-form__flex-input">
        <p>支出カテゴリー</p>
        <select name="filtering-spendingcat" id="">
            <option value="0">選択してください</option>
            <?php
            $sql = "SELECT id, name FROM spending_category WHERE user_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("i", $user["id"]);
            sql_check($stmt, $db);
            $stmt->bind_result($id, $name);
            while ($stmt->fetch()) : ?>
                <option value="<?php echo h($id); ?>" <?php echo $filtering_spendingcat == $id ? "selected" : ""; ?>><?php echo h($name); ?></option>
            <?php endwhile; ?>
        </select>
        <i class="fa-solid fa-angle-down"></i>
    </div>

    <div class="p-form__flex-input">
        <p>収入カテゴリー</p>
        <select name="filtering-incomecat" id="">
            <option value="0">選択してください</option>
            <?php
            $sql = "SELECT id, name FROM income_category WHERE user_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("i", $user["id"]);
            sql_check($stmt, $db);
            $stmt->bind_result($id, $name);
            while ($stmt->fetch()) : ?>
                <option value="<?php echo h($id); ?>" <?php echo $filtering_incomecat == $id ? "selected" : ""; ?>><?php echo h($name); ?></option>
            <?php endwhile; ?>
        </select>
        <i class="fa-solid fa-angle-down"></i>
    </div>

    <?php if ($select === "adult") : ?>
        <div class="p-form__flex-input">
            <p>支払い方法</p>
            <select name="filtering-paymentmethod" id="">
                <option value="0">選択してください</option>
                <?php
                $sql = "SELECT id, name FROM payment_method WHERE user_id = 0 OR user_id = ?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("i", $user["id"]);
                sql_check($stmt, $db);
                $stmt->bind_result($id, $name);
                while ($stmt->fetch()) : ?>
                    <option value="<?php echo h($id); ?>" <?php echo $filtering_paymentmethod == $id ? "selected" : ""; ?>><?php echo h($name); ?></option>
                <?php endwhile; ?>
            </select>
            <i class="fa-solid fa-angle-down"></i>
        </div>

        <div class="p-form__flex-input">
            <p>クレジットカード</p>
            <select name="filtering-credit" id="">
                <option value="0">選択してください</option>
                <?php
                $sql = "SELECT id, name FROM creditcard WHERE user_id = ?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("i", $user["id"]);
                sql_check($stmt, $db);
                $stmt->bind_result($id, $name);
                while ($stmt->fetch()) : ?>
                    <option value="<?php echo h($id); ?>" <?php echo $filtering_credit == $id ? "selected" : ""; ?>><?php echo h($name); ?></option>
                <?php endwhile; ?>
            </select>
            <i class="fa-solid fa-angle-down"></i>
        </div>

        <div class="p-form__flex-input">
            <p>スマホ決済</p>
            <select name="filtering-qr" id="">
                <option value="0">選択してください</option>
                <?php
                $sql = "SELECT id, name FROM qr WHERE user_id = ?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("i", $user["id"]);
                sql_check($stmt, $db);
                $stmt->bind_result($id, $name);
                while ($stmt->fetch()) : ?>
                    <option value="<?php echo h($id); ?>" <?php echo $filtering_qr == $id ? "selected" : ""; ?>><?php echo h($name); ?></option>
                <?php endwhile; ?>
            </select>
            <i class="fa-solid fa-angle-down"></i>
        </div>
    <?php endif; ?>

    <input type="submit" class="c-button c-button--bg-blue" name="detail-search" value="絞り込み検索" onclick="onRemoveSearchModal();">
    <input type="submit" class="c-button c-button--bg-gray" name="detail-reset" value="絞り込みリセット" onclick="onRemoveSearchModal();">
</form>
<?php if (isset($_POST["detail-search"])) : ?>
    <div class="p-search-word">
        <p>絞り込み中：
            <!-- タイトルキーワードが空欄でなければ送信された値を出力 -->
            <?php if ($filtering_title != null) : ?>
                <span><?php echo h($filtering_title); ?></span>
            <?php endif; ?>

            <?php
            //以下1行移動させる
            $filter_value = [$filtering_child, $filtering_spendingcat, $filtering_incomecat, $filtering_paymentmethod, $filtering_credit, $filtering_qr];

            //選択項目テーブルリスト配列
            $table_list = ["child", "spending_category", "income_category", "payment_method", "creditcard", "qr"];
            ?>

            <?php
            for ($i = 0; $i < count($filter_value); $i++) :
                //各選択項目の値が0(選択してください)以外なら以下を実行
                if ($filter_value[$i] !== "0") :
                    $sql = "SELECT name FROM {$table_list[$i]} WHERE id=?";
                    $stmt = $db->prepare($sql);
                    $stmt->bind_param("i", $filter_value[$i]);
                    sql_check($stmt, $db);
                    $stmt->bind_result($name);
                    $stmt->fetch();
                    echo "<span>" . h($name) . "</span>";
                    $stmt->close();
                endif;
            endfor;
            ?>
        </p>
    </div>
<?php endif; ?>