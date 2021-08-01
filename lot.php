<?php
require_once 'helpers.php';
require_once 'function.php';
require_once 'data.php';

require_once 'init.php';
require_once 'models.php';


if (!$link) {
    $error = mysqli_connect_error($link);
    $content = include_template('error.php', ['error' => $error]);
}
else {
    $lot_id = filter_input(INPUT_GET, 'id');
    $categories = get_catigories($link);
    if(!$lot_id) {

        $menu = include_template('menu.php', [
            'categories' => $categories]);

        $page_content = include_template('404.php', [
            'menu' => $menu]);
    } else {
        $lots = get_ads($link);
        $lot_in_lots = in_array($lot_id, array_column($lots, 'id'));
        if (!$lot_in_lots) {
            $menu = include_template('menu.php', [
                'categories' => $categories]);
            $page_content = include_template('404.php', [
                'menu' => $menu]);
        } else {
            $lot = get_lot($link, $lot_id);
            $lot['min_price'] = $lot['step_bet'] + $lot['price'];
            $menu = include_template('menu.php', [
                'categories' => $categories]);
            $page_content = include_template('lot.php', [
                'menu' => $menu,
                'lot' => $lot
            ]);
        }
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
