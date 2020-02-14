<?php

/**
 * Класс массива.
 */
class FoundationArray {
  protected $data;

  /**
   * Конструктор класса.
   *
   * @param array $data
   *   Массив данных.
   *
   * @return FoundationArray
   *   Объект массива.
   */
  public static function factory(array $data = array()) {
    return new FoundationArray($data);
  }

  /**
   * Конструктор класса.
   *
   * @param array $data
   *   Массив данных.
   */
  public function __construct(array $data = array()) {
    $this->data = $data;
  }

  /**
   * Геттер массива.
   *
   * @return array
   *   Массив данных.
   */
  public function toArray() {
    return $this->data;
  }

  /**
   * Геттер значения.
   *
   * @param string|array $path
   *   Путь.
   * @param bool $exists
   *   Признак существования.
   *
   * @return mixed
   *   Значение.
   */
  public function get($path, &$exists = NULL) {
    if (is_array($path)) {
      $value = drupal_array_get_nested_value($this->data, $path, $exists);
    }
    else {
      $value = isset($this->data[$path]) ? $this->data[$path] : NULL;
    }
    return $value;
  }

  /**
   * Сеттер значения.
   *
   * @param mixed $path
   *   Путь.
   * @param mixed $value
   *   Значение.
   * @param bool $force
   *   Признак принудительного пути.
   */
  public function set($path, $value, $force = TRUE) {
    if (is_array($path)) {
      drupal_array_set_nested_value($this->data, $path, $value, $force);
    }
    else {
      $this->data[$path] = $value;
    }
  }

}
