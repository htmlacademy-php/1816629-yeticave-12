<?php
require('helpers.php');
require('function.php');
require('data.php');
require_once 'init.php';

if (!$link) {
    $error = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
}
else {
    $sql = 'SELECT `name`, `code` FROM categories';
    $result = mysqli_query($link, $sql);

    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        $error = mysqli_error($link);
        $content = include_template('error.php', ['error' => $error]);
    }

    $sql_ads = 'SELECT a.id, a.name, a.start_price, a.img, a.date_end, c.name AS category, IFNULL(MAX(b.price), a.start_price) AS price '
        . 'FROM ads AS a '
        . 'INNER JOIN categories AS c ON c.id = a.category_id '
        . 'LEFT JOIN bets AS b ON b.ad_id = a.id '
        . 'WHERE a.date_end > NOW() '
        . 'GROUP BY a.id; ';
    if ($res = mysqli_query($link, $sql_ads)) {
        $ads = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    else {
        $error = mysqli_error($link);
        $content = include_template('error.php', ['error' => $error]);
    }
}


$page_content = include_template('main.php', [
    'categories' => $categories,
    'ads' => $ads]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Главная',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
