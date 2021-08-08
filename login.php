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

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_login = $_POST;

    $required_fields = [
        'email',
        'password'
    ];

    $rules = [
        'email' => function ($value)  {
            return validate_email_login($value);
        }
    ];

    $errors = form_validation($form_login, $rules, $required_fields);

    if (!$errors) {
        $sql = 'SELECT id, email, name, password
            FROM users
            WHERE email = ?
            LIMIT 1';

        $stmt = db_get_prepare_stmt($link, $sql, [$form_login['email']]);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($res);

        if ($user) {
            if (password_verify($form_login['password'], $user['password'])) {
                $_SESSION['user'] = $user;
                header("Location: /index.php");
                die();
            } else {
                $errors['password'] = 'Неверный пароль';
            }
        } else {
            $errors['email'] = 'Такой пользователь не найден';
        }

    }
}



$menu = include_template('menu.php', [
    'categories' => $categories]);

$page_content = include_template('login.php', [
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
