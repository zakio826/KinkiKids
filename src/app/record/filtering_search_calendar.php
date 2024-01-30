<a href="#modal_calendar" class="p-banner-calendar c-button c-button--bg-blue js-detail-search">絞り込み検索</a>

<form method="POST" class="p-form--detail-search remodal" data-remodal-id="modal_calendar">
    <button data-remodal-action="close" class="remodal-close"></button>
        <div class="p-form__flex-input">
            <p>子どもの名前</p>
            <select name="calendar-filtering-child" id="">
                <option value="0">選択してください</option>
                <?php
                $sql = "SELECT id, name FROM child WHERE parent = ?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("i", $user["id"]);
                sql_check($stmt, $db);
                $stmt->bind_result($id, $name);
                while ($stmt->fetch()) :
                ?>
                <option value="<?php echo h($id); ?>" <?php echo $calendar_filtering_child == $id ? "selected" : ""; ?>><?php echo h($name); ?></option>
                <?php endwhile; ?>
            </select>
            <i class="fa-solid fa-angle-down"></i>
        </div>

    <div class="p-form__flex-input">
        <p>支出カテゴリー</p>
        <select name="calendar-filtering-spendingcat" id="">
            <option value="0">選択してください</option>
            <?php
            $sql = "SELECT id, name FROM spending_category WHERE user_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("i", $user["id"]);
            sql_check($stmt, $db);
            $stmt->bind_result($id, $name);
            while ($stmt->fetch()) : ?>
                <option value="<?php echo h($id); ?>" <?php echo $calendar_filtering_spendingcat == $id ? "selected" : ""; ?>><?php echo h($name); ?></option>
            <?php endwhile; ?>
        </select>
        <i class="fa-solid fa-angle-down"></i>
    </div>

    <div class="p-form__flex-input">
        <p>収入カテゴリー</p>
        <select name="calendar-filtering-incomecat" id="">
            <option value="0">選択してください</option>
            <?php
            $sql = "SELECT id, name FROM income_category WHERE user_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("i", $user["id"]);
            sql_check($stmt, $db);
            $stmt->bind_result($id, $name);
            while ($stmt->fetch()) : ?>
                <option value="<?php echo h($id); ?>" <?php echo $calendar_filtering_incomecat == $id ? "selected" : ""; ?>><?php echo h($name); ?></option>
            <?php endwhile; ?>
        </select>
        <i class="fa-solid fa-angle-down"></i>
    </div>

    <input type="submit" class="c-button c-button--bg-blue" name="detail-search-calendar" value="絞り込み検索" onclick="onRemoveSearchModal();">
    <input type="submit" class="c-button c-button--bg-gray" name="detail-reset" value="絞り込みリセット" onclick="onRemoveSearchModal();">
</form>
<?php if (isset($_POST["detail-search-calendar"])) : ?>
    <div class="p-search-word">
        <p>絞り込み中：
            <?php
            $filter_value = [$calendar_filtering_child, $calendar_filtering_spendingcat, $calendar_filtering_incomecat];

            //選択項目テーブルリスト配列
            $table_list = ["child", "spending_category", "income_category"];
            ?>

            <?php
            for ($i = 0; $i < count($filter_value); $i++) :
                //各選択項目の値が0(選択してください)以外なら以下を実行
                if ($filter_value[$i] !== "0") :
                    $sql = "SELECT name FROM {$table_list[$i]} WHERE id=?";
                    $stmt = $db->prepare($sql);
                    $stmt->bind_param("i", $filter_value[$i]);
                    $count = sql_check($stmt, $db);
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