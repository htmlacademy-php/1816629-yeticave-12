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
    $categories = get_catigories($link);
}

$menu = include_template('menu.php', [
    'categories' => $categories]);

$page_content = include_template('login.php', [
    'menu' => $menu]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Главная',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
