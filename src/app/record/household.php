<section id="household" class="p-section p-section__records-input js-switch-content fade-in" data-tab="tab-4">
    <ul class="household_switch <?php echo $time->format("H:i") < "19:00" ? "daytime" : "night"; ?>" id="switch">
        <li class="household_switch__item is-active" data-switch="switch-0">
            <i class="fa-solid fa-calendar"></i>
            カレンダー
        </li>
        <li class="household_switch__item" data-switch="switch-1">
            <i class="fa-solid fa-pen"></i>
            入力
        </li>
    </ul>
    <div class="household">
        <div id="calendar" class="household-calendar switch-household hide" data-switch="switch-0">
            <?php
            $type = "household";
            // カレンダー画面（家計簿画面）
            include("./component/household/calendar.php");
            ?>
        </div>
            <?php
            // 家計簿の入力画面
            include_once("./component/household/record-input.php");
            ?>
    </div>
</section>