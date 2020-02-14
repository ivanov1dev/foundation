<?php

/**
 * Класс фабрики сериализаторов.
 */
final class SerializerFactory {
  private static $serializers = array();

  /**
   * Геттер объекта.
   *
   * @param string $name
   *   Имя сериализатора.
   *
   * @return SerializerInterface
   *   Объект консьюмера.
   */
  public static function get($name) {
    if (isset(self::$serializers[$name])) {
      return self::$serializers[$name];
    }

    $class_name = 'Serializer' . FoundationString::className($name);
    if (!class_exists($class_name)) {
      throw new \InvalidArgumentException(sprintf('Класс сериалайзера "%s" не определен', $class_name));
    }

    self::$serializers[$name] = new $class_name();
    return self::$serializers[$name];
  }

}
