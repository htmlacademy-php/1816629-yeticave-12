<?php

/**
 * Функция форматирования суммы и добавления к ней знака рубля
 * @param $number цена
 * @return string отформатированная цена со знаком рубля
 */
function price_format($number) {
    $number_ceil = ceil($number);
    return number_format($number_ceil, 0, ',', ' ') . ' ₽';
};


/**
 * Функция вычисления количества часов и минут до окончания публикации
 * @param $data дата окончания публикации
 * @return array часы и минуты до  окончания публикации
 */
function get_data_range($date){
    $date_now = date_create("now");
    $date_end = date_create($date);
    $diff = date_diff($date_now, $date_end);
    $days_count = date_interval_format($diff, "%a");
    $hours_count = date_interval_format($diff, "%h");
    $all_hourse = $days_count * 24 + $hours_count;
    $minutes_count = date_interval_format($diff, "%i");
    $diff_time = [$all_hourse, $minutes_count];
    return $diff_time;
}

