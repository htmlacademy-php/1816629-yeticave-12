<?php
require_once 'helpers.php';
require_once 'function.php';
require_once 'data.php';

require_once 'init.php';
require_once 'models.php';

if (isset($_SESSION['user'])) {
    http_response_code(403);
    die();
}

if (!$link) {
    $error = mysqli_connect_error($link);
    $content = include_template('error.php', ['error' => $error]);
}
else {
    $categories = get_catigories($link);
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form_sign_up = $_POST;

    $required_fields = [
        'email',
        'password',
        'name',
        'message'
    ];

    $rules = [
        'email' => function ($value) use ($link) {
            return validate_email($value, $link);
        }
    ];

    $errors = form_validation($form_sign_up, $rules, $required_fields);

    if (!$errors) {
        $password = password_hash($form_sign_up['password'], PASSWORD_DEFAULT);

        $sql = 'INSERT INTO users (date_registration, email, password, name, contacts) VALUES (NOW(), ?, ?, ?, ?)';

        $stmt = db_get_prepare_stmt($link, $sql, [$form_sign_up['email'], $password, $form_sign_up['name'], $form_sign_up['message']]);
        $res = mysqli_stmt_execute($stmt);

       header("Location: /login.php");
       die();
    }

}

$menu = include_template('menu.php', [
    'categories' => $categories]);

$page_content = include_template('sign_up.php', [
    'menu' => $menu,
    'errors' => $errors]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Главная',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
