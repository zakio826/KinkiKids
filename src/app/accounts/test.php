<?php
//日付と時間
$post_date= date('Ymd');
$post_time = date('His');

//次に有効な月の〇日をunixtimeで返す関数
$next_schedule = next_schedule_monthly ( $post_date, $post_time );

function next_schedule_monthly ( $post_date, $post_time ) {

  //01234567
  //YYYYMMDD
    $datetime['year']   = (int)substr($post_date, 0, 4);
    $datetime['month']  = (int)substr($post_date, 4, 2);
    $datetime['day']    = (int)substr($post_date, 6, 2);
    //012345
    //HHMMSS
    $datetime['second'] = 0;
    $datetime['minute'] = (int)substr($post_time, 2, 2);
    $datetime['hour']   = (int)substr($post_time, 0, 2);

    $check_month = $datetime['month'];

    $check_flag = true;

    for($i=1; $i<13; $i++) {

    //翌月作成
    $check_month++;

    //12月以内に変更する
    if($check_month >= 13) {
        $check_month = $check_month - 12;

        if($check_flag) {
        $datetime['year'] = $datetime['year'] + 1;
        $check_flag = false;
        }
    }

    //正しい日付かをチェックする
    if( checkdate( $check_month, $datetime['day'], $datetime['year'] ) ) {
        break;
    }
    }

    //UTC+9とUTCに変換
    $schedule_time['utc_jp'] = mktime($datetime['hour'], $datetime['minute'], $datetime['second'], $check_month, $datetime['day'], $datetime['year']);
    $schedule_time['utc'] = $schedule_time['utc_jp'] - 32400;

    return $schedule_time;

}