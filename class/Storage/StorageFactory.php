<?php

/**
 * Класс фабрики.
 */
class StorageFactory {
  private static $storageName = 'file_system';
  private static $basePath = 'data';
  private static $cache = array();

  /**
   * Сеттер имени хранилища.
   *
   * @param string|null $name
   *   Имя хранилища.
   */
  public static function storageName($name) {
    self::$storageName = $name;
  }

  /**
   * Сеттер базового пути.
   *
   * @param string|null $path
   *   Базовый путь.
   */
  public static function basePath($path) {
    self::$basePath = $path;
  }

  /**
   * Геттер хранилища.
   *
   * @param string|null $storage_name
   *   Имя хранилища.
   *
   * @return StorageInterface
   *   Объект.
   */
  public static function get($storage_name = NULL) {
    if (!$storage_name) {
      $storage_name = self::$storageName;
    }

    if (isset(self::$cache[$storage_name])) {
      return self::$cache[$storage_name];
    }

    $class = 'Storage' . FoundationString::className($storage_name);
    if (!class_exists($class)) {
      throw new \InvalidArgumentException('Класс хранилища не определен');
    }

    self::$cache[$storage_name] = new $class(self::$basePath);

    return self::$cache[$storage_name];
  }

}
