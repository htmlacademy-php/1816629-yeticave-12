<?php
require('helpers.php');
require('function.php');
require('data.php');

require_once 'init.php';
require('models.php');


if (!$link) {
    $error = mysqli_connect_error($link);
    $content = include_template('error.php', ['error' => $error]);
}
else {
    $categories = get_catigories($link);
    $ads = get_ads($link);
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
