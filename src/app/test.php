<?php
// 日付の指定
$post_date = '20240210'; // YYYYMMDD

// 次に有効な月の〇日をunixtimeで返す関数
$next_schedule = next_schedule_monthly($post_date);

function next_schedule_monthly($post_date)
{
    //01234567
    //YYYYMMDD
    $datetime['year']   = (int)substr($post_date, 0, 4);
    $datetime['month']  = (int)substr($post_date, 4, 2);
    $datetime['day']    = (int)substr($post_date, 6, 2);
    //012345
    //HHMMSS
    $datetime['second'] = 0;
    $datetime['minute'] = 0;
    $datetime['hour']   = 0;

    $check_month = $datetime['month'];

    $check_flag = true;

    for ($i = 1; $i < 13; $i++) {
        //翌月作成
        $check_month++;

        //12月以内に変更する
        if ($check_month >= 13) {
            $check_month = $check_month - 12;

            if ($check_flag) {
                $datetime['year'] = $datetime['year'] + 1;
                $check_flag = false;
            }
        }

        //正しい日付かをチェックする
        if (checkdate($check_month, $datetime['day'], $datetime['year'])) {
            break;
        }
    }

    // 日本時間に変換
    $schedule_time['jp'] = mktime($datetime['hour'], $datetime['minute'], $datetime['second'], $check_month, $datetime['day'], $datetime['year']);

    return $schedule_time;
}

// 結果の表示
echo "指定された日付: " . $post_date . PHP_EOL;
echo "次のスケジュールの日時（日本時間）: " . date('Y-m-d', $next_schedule['jp']) . PHP_EOL;
?>
