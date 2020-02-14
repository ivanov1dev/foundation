<?php

/**
 * Класс фабрики бмлдеров.
 */
final class BuilderFactory {
  private static $builders = array();

  /**
   * Геттер объекта.
   *
   * @param string $name
   *   Наименование.
   *
   * @return BuilderInterface
   *   Объект билдера.
   */
  public static function get($name) {
    if (isset(self::$builders[$name])) {
      return self::$builders[$name];
    }

    $class = 'Builder' . FoundationString::className($name);
    if (!class_exists($class)) {
      throw new \InvalidArgumentException(sprintf('Класс билдера "%s" не определен', $class));
    }

    self::$builders[$name] = new $class();

    return self::$builders[$name];
  }

}
