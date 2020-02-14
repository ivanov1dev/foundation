<?php

/**
 * Класс функций для работы с полями.
 */
final class FoundationField {

  /**
   * Геттер значений словаря.
   *
   * @param string $field_name
   *   Имя поля.
   * @param mixed $key
   *   (опционально) Ключ для конкретного значения.
   *
   * @return mixed
   *   Значение элемента.
   */
  public static function allowedValues($field_name, $key = NULL) {
    $cache = &drupal_static(__FUNCTION__);

    if (empty($cache[$field_name])) {
      $field = field_info_field($field_name);
      $cache[$field_name] = list_allowed_values($field);
    }

    $result = $cache[$field_name];

    if ($key) {
      $result = isset($result[$key]) ? $result[$key] : NULL;
    }

    return $result;
  }

  /**
   * Значение словаря по ключу.
   *
   * @param string $field
   *   Имя поля.
   * @param mixed $key
   *   Ключ для конкретного значения.
   *
   * @return mixed
   *   Значение элемента.
   */
  public static function dictValue($field, $key) {
    return self::allowedValues($field, $key);
  }

  /**
   * Значение словаря по ключу.
   *
   * @param string $field
   *   Имя поля.
   * @param array $keys
   *   Ключи.
   *
   * @return mixed
   *   Значение элемента.
   */
  public static function dictValues($field, array $keys) {
    $result = array();
    $dictionary = self::allowedValues($field);
    foreach ($keys as $key) {
      $result[$key] = isset($dictionary[$key]) ? $dictionary[$key] : '';
    }
    return array_filter($result);
  }

}
