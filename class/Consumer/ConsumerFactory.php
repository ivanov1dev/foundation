<?php

/**
 * Класс фабрики косьюмеров.
 */
final class ConsumerFactory {

  /**
   * Геттер объекта консьюмера.
   *
   * @param string $name
   *   Имя консьюмера.
   *
   * @return ConsumerInterface
   *   Объект консьюмера.
   */
  public static function get($name) {
    $class_name = 'Consumer' . FoundationString::className($name);
    if (!class_exists($class_name)) {
      throw new \InvalidArgumentException(sprintf('Класс консьюмера "%s" не определен', $class_name));
    }
    return new $class_name();
  }

}
