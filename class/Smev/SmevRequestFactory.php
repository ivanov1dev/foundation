<?php

/**
 * Класс фабрики.
 */
final class SmevRequestFactory {
  private static $cache = array();
  private static $scheme;
  private static $topic;
  private static $key;

  /**
   * Сеттер топика.
   *
   * @inheritDoc
   */
  public static function topic($name) {
    self::$topic = $name;
  }

  /**
   * Сеттер схемы.
   *
   * @inheritDoc
   */
  public static function scheme($name) {
    self::$scheme = $name;
  }

  /**
   * Сеттер ключа.
   *
   * @inheritDoc
   */
  public static function key($name) {
    self::$key = $name;
  }

  /**
   * Геттер объекта.
   *
   * @param string $name
   *   Имя.
   *
   * @return SmevRequestInterface
   *   Объект.
   */
  public static function get($name) {
    if (isset(self::$cache[$name])) {
      return self::$cache[$name];
    }

    $class_name = 'SmevRequest' . FoundationString::className($name);
    if (!class_exists($class_name)) {
      throw new \InvalidArgumentException(sprintf('Класс СМЭВ запроса "%s" не определен.', $class_name));
    }

    self::$cache[$name] = new $class_name();
    self::$cache[$name]->topic(self::$topic);
    self::$cache[$name]->scheme(self::$scheme);
    self::$cache[$name]->key(self::$key);
    return self::$cache[$name];
  }


}
