<?php

/**
 * Функция получения из БД массива категорий
 * @param $link подключение к БД
 * @return array массив категорий
 */
function get_catigories ($link) {
    $sql = 'SELECT `name`, `code` FROM categories';
    $result = mysqli_query($link, $sql);

    if ($result) {
        return $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        $error = mysqli_error($link);
        $content = include_template('error.php', ['error' => $error]);
    }
};

/**
 * Функция получения из БД массива актуальных объявлений
 * @param $link подключение к БД
 * @return array массив актуальных объявлений
 */
function get_ads ($link) {
    $sql_ads = 'SELECT a.id, a.name, a.start_price, a.img, a.date_end, c.name AS category, IFNULL(MAX(b.price), a.start_price) AS price '
        . 'FROM ads AS a '
        . 'INNER JOIN categories AS c ON c.id = a.category_id '
        . 'LEFT JOIN bets AS b ON b.ad_id = a.id '
        . 'WHERE a.date_end > NOW() '
        . 'GROUP BY a.id; ';
    if ($res = mysqli_query($link, $sql_ads)) {
        return $ads = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    else {
        $error = mysqli_error($link);
        $content = include_template('error.php', ['error' => $error]);
    }
}
