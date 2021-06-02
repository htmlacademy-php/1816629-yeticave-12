<?php

/** Функция форматирования суммы и добавления к ней знака рубля
 * @param $number цена
 * @return string отформатированная цена со знаком рубля
 */
function price_format($number) {
    $number_ceil = ceil($number);
    return number_format($number_ceil, 0, ',', ' ') . ' ₽';
};
