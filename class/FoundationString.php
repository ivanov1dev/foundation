<?php

/**
 * Класс по работе со строками.
 */
final class FoundationString {

  /**
   * Подготовка имени класса.
   *
   * @param string $string
   *   Строка текста.
   * @param string $delimiters
   *   Строка разделителей.
   *
   * @return string
   *   Имя класса.
   */
  public static function className($string, $delimiters = '_-') {
    $string = str_replace(' ', '_', $string);
    $items = preg_split('/[' . $delimiters . ']/', $string);
    return implode('', array_map('drupal_ucfirst', $items));
  }

  /**
   * Значение по умолчанию для переменной.
   *
   * @param string $variable
   *   Проверяемое значение.
   * @param string $value
   *   Возвращаемое значение.
   * @param string $default
   *   (опционально) Значение по умолчанию.
   *
   * @return string
   *   Имя класса.
   */
  public static function value($variable, $value, $default = NULL) {
    return $variable ? $value : $default;
  }

  /**
   * Подготовка HTML тэга.
   *
   * @param string $tag
   *   HTML тэг.
   * @param string|array $content
   *   Текст содержимого.
   * @param array $attributes
   *   (опционально) Массив атрибутов.
   *
   * @return string
   *   HTML код.
   */
  public static function tag($tag, $content, array $attributes = array()) {
    if (!is_array($content)) {
      $content = array($content);
    }

    foreach ($content as &$row) {
      $row = sprintf('<%s%s>%s</%s>', $tag, drupal_attributes($attributes), $row, $tag);
    }

    return implode('', $content);
  }

}
