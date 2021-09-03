<?php

require_once 'config/db.php';
require_once 'function.php';
require_once 'models.php';

session_start();

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, "utf8");

if (!$link) {
    $error = mysqli_connect_error($link);
    $page_content = include_template('error.php', ['error' => $error]);

    print($page_content);
    die();
}

$categories = get_catigories($link);




