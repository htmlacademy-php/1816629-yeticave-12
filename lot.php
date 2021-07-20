<?php
require('helpers.php');
require('function.php');
require('data.php');

require('init.php');
require('models.php');


if (!$link) {
    $error = mysqli_connect_error($link);
    $content = include_template('error.php', ['error' => $error]);
}
else {
    $lot_id = filter_input(INPUT_GET, 'id');
    if(!$lot_id) {
        $categories = get_catigories($link);
        $page_content = include_template('404.php', [
            'categories' => $categories]);
    } else {
        $lot = get_lot($link, $lot_id);
        $categories = get_catigories($link);
        $lot['min_price'] = $lot['step_bet'] + ($lot['price']);

        $page_content = include_template('lot.php', [
            'categories' => $categories,
            'lot' => $lot
        ]);
    }

}


$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Страница лота',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
