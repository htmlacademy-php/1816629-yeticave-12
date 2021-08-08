<?php

/**
 * Функция форматирования суммы и добавления к ней знака рубля
 * @param $number цена
 * @return string отформатированная цена со знаком рубля
 */
function price_format($number) {
    $number_ceil = ceil($number);
    return number_format($number_ceil, 0, ',', ' ') . ' ₽';
};


/**
 * Функция вычисления количества часов и минут до окончания публикации
 * @param $data дата окончания публикации
 * @return array часы и минуты до  окончания публикации
 */
function get_data_range($date){
    $date_now = date_create("now");
    $date_end = date_create($date);
    $diff = date_diff($date_now, $date_end);
    $days_count = date_interval_format($diff, "%a");
    $hours_count = date_interval_format($diff, "%h");
    $all_hourse = $days_count * 24 + $hours_count;
    $minutes_count = date_interval_format($diff, "%i");
    $diff_time = [$all_hourse, $minutes_count];
    return $diff_time;
}

/** Функция проверки, что начальная цена - число больше нуля.
 * @param $value цена
 * @return string|null ошибка
 */
function validate_price($value) {
    if (!is_double($value) || $value <= 0) {
        return "Начальная цена должна больше 0";
    }
}

/** Функция проверки, что шаг ставки - целое число больше 0.
 * @param $value число
 * @return string ошибка
 */
function validate_step_rate($value) {
    if (!is_int($value) || $value <= 0) {
        return "Шаг ставки должен быть целым числом больше 0";
    }
}

/** Функция проверки, что переданная дату завершения больше текущей даты на один день.
 * @param $date дата
 * @return string ошибка
 */
function validate_current_date($date){
    if (is_date_valid($date) && get_data_range($date)[0] < "24" || strtotime($date) < strtotime("today")) {
        return "Дата должна быть больше текущей даты хотя бы на 1 день.";
    }
}

/** Функция проверки выбрана ли категория лота из списка.
 * @param $id id выбранной категории
 * @param $category массмв категорий
 * @return string ошибка
 */
function validate_category_id($id, $category){
    if (!in_array($id, $category)) {
        return "Выберите категорию из списка";
    }
}

/** Проверяет расширение загруженного файла.
 * @param $file загруженный файл
 * @return string ошибка
 */
function validate_file($file) {
    $info = new SplFileInfo($file['name']);
    $info_file = mb_strtolower($info->getExtension());

    if (!in_array($info_file, ['jpeg', 'jpg', 'png'])) {
        return $errors['lot-img'] = 'Загрузите картинку в формате JPG, JPEG или PNG';
    } else {
        $mimetype = mime_content_type($file['tmp_name']);
        if (!(in_array($mimetype, ['image/jpeg', 'image/png']))) {
            return $errors['lot-img'] = 'Загрузите картинку в формате JPG, JPEG или PNG';
        }
    }


}

/**
 * @param string $value значение глобального супермассива POST по ключу
 * @return string если true - возвращает значение глобального массива Post по ключу. если false - пустую строку
 */
function get_post_val($name) {
    return filter_input(INPUT_POST, $name);
}

/** Функция проверки email
 * @param $value введенный email
 * @param $link подключение к бд
 * @return string ошибка
 */
function validate_email ($value, $link) {
    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        return $errors['email'] = "Введите корректный email";
    }
    $sql = 'SELECT id
            FROM users
            WHERE email = ?
            LIMIT 1';

    $stmt = db_get_prepare_stmt($link, $sql, [$value]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $user_id = mysqli_fetch_assoc($res);

    if ($user_id > 0) {
        return $errors['email'] = "Пользователь с этим email уже зарегистрирован";
    }
}

/** Функция проверки email
 * @param $value введенный email
 * @param $link подключение к бд
 * @return string ошибка
 */
function validate_email_login ($value) {
    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        return $errors['email'] = "Введите корректный email";
    } return null;
}

/** Функция валидация формы
 * @param $form данные из формы
 * @param $rules правила валидации
 * @param $required массив обязательных полей
 * @return array массив ошибок
 */
function form_validation($form, $rules, $required) {
    $errors = [];
    foreach ($form as $key => $value) {
        if (in_array($key, $required) && empty($value)) {
            $errors[$key] = "Заполните это поле";
        } elseif (isset($rules[$key])) {
            $rule = $rules[$key];
            $validationResult = $rule($value);
            if ($validationResult) {
                $errors[$key] = $validationResult;
            }
        }
    }
    return $errors;
}
