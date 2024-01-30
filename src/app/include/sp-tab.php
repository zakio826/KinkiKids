<!-- スマホ用切り替えタブ -->
<?php if (!isset($_GET["detail"])) : ?>
    <ul class="p-switch-tab" id="tab">
        
        <!-- ホームアイコン -->
        <li class="p-switch-tab__item is-active" data-tab="0">
            <!-- <i class="fa-solid fa-house"></i> -->
            <img src="./img/house.png">
            <p>ホーム</p>
        </li>

        <li class="p-switch-tab__item" data-tab="5">
            <?php
            if ($select === "child") {
                $cols = array(
                    "number",
                    "id",
                    "date",
                    "mission",
                    "flag",
                    "point",
                );
                $wheres = array(
                    "id" => ["=", "i", $user["parent"]],
                    "flag" => ["=", "i", 0],
                    "date" => ["=", "s", $time->format("Y-m-d")],
                );
                $order = array(
                    // "order" => ["id", true],
                );
                $result = select($line, $cols, "EmergencyMission", wheres: $wheres, group_order: $order);
            }
            ?>

            <?php if ($select == "child" && count($result) > 0 && $result[0]["flag"] == 0) : ?>
                <img src="./img/mission_emergent.png">
            <?php else : ?>
                <img src="./img/mission.png">
            <?php endif; ?>
            
            <p>お手伝い</p>
            <!-- <img src="./img/household.png" id="householdBtn" data-tab="5"> -->
        </span>

        <!-- 銀行アイコン -->
        <li class="p-switch-tab__item" data-tab="1">
            <!-- <i class="fa-solid fa-coins"></i> -->
            <img src="./img/Coin.png">

            <!-- 親ユーザーか子どもユーザーかで漢字表記が変更される -->
            <p><?php echo $select === "adult" ? "銀行" : "ぎんこう" ?></p>
        </li>

        <!-- ふりかえりアイコン -->
        <li class="p-switch-tab__item" data-tab="2">
            <!-- <i class="fa-solid fa-clipboard-list"></i> -->
            <img src="./img/Calendar.png">

            <!-- 親ユーザーか子どもユーザーかで漢字表記が変更される -->
            <p><?php echo $select === "adult" ? "振り返り" : "ふりかえり" ?></p>
        </li>

        <!-- 設定アイコン -->
        <li class="p-switch-tab__item" data-tab="3">
            <!-- <i class="fa-solid fa-book"></i> -->
            <img src="./img/Cog.png">
            <p>設定</p>
        </li>

        <!-- <li class="p-switch-tab__item" data-tab="tab-5">
            
        </li> -->
    </ul>
    
<?php endif; ?>

<!-- スマホ用切り替えタブ -->