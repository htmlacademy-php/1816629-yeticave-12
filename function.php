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
 * @param $value
 * @return string|null
 */
function validate_price($value) {
    if (!is_double($value) || $value <= 0) {
        return "Начальная цена должна больше 0";
    }
}

/** Функция проверки, что шаг ставки - целое число больше 0.
 * @param $value
 * @return string
 */
function validate_step_rate($value) {
    if (!is_int($value) || $value <= 0) {
        return "Шаг ставки должен быть целым числом больше 0";
    }
}

/** Функция проверки, что переданная дату завершения больше текущей даты на один день.
 * @param $date
 * @return string
 */
function validate_current_date($date)
{

    if (is_date_valid($date) && get_data_range($date)[0] < "24" || strtotime($date) < strtotime("today")) {
        return "Дата должна быть больше текущей даты хотя бы на 1 день.";
    }
}

/** Функция проверки выбрана ли категория лота из списка.
 * @param $id
 * @param $category_list
 * @return string
 */
function validate_category_id($id, $category)
{
    if (!in_array($id, $category)) {
        return "Выберите категорию из списка";
    }
}

/** Проверяет расширение загруженного файла.
 * @param $file
 * @return string
 */
function validate_file($file) {

    $mimetype = mime_content_type($file['tmp_name']);
    if (!(in_array($mimetype, ['image/jpeg', 'image/png']))) {
        return $errors['lot-img'] = 'Загрузите картинку в формате JPG, JPEG или PNG';
    }
}

/**
 * @param string $value значение глобального супермассива POST по ключу
 * @return string если true - возвращает значение глобального массива Post по ключу. если false - пустую строку
 */
function get_post_val($name)
{
    return filter_input(INPUT_POST, $name);
}
