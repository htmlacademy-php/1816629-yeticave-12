<?php
require('helpers.php');
require('function.php');
require('data.php');
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
