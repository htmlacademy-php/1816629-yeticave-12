<?php
require_once 'helpers.php';
require_once 'function.php';
require_once 'data.php';

require_once 'init.php';
require_once 'models.php';

if (!isset($_SESSION['user'])) {
    http_response_code(403);
    die();
}

if (!$link) {
    $error = mysqli_connect_error($link);
    $content = include_template('error.php', ['error' => $error]);
}
else {
    $categories = get_catigories($link);

    $user_id = $_SESSION['user']['id'];
    //$user_id = 14;
    $bets = get_my_bets($link, $user_id);
}



$menu = include_template('menu.php', [
    'categories' => $categories]);

$page_content = include_template('my_bets.php', [
    'menu' => $menu,
    'bets' => $bets,
    'categories' => $categories,
    'user_id' => $user_id
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Главная',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
