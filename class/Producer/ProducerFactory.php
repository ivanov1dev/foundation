<?php

/**
 * Класс фабрики продюсеров.
 */
final class ProducerFactory {
  private static $producers = array();

  /**
   * Геттер объекта.
   *
   * @param string $id
   *   Идентификатор.
   * @param string $serializer_name
   *   Имя сериализатора.
   * @param string $producer_name
   *   Имя продюсера.
   *
   * @return ProducerInterface
   *   Объект консьюмера.
   */
  public static function get($id = 'foundation', $serializer_name = 'json', $producer_name = 'default') {
    $key = implode('::', func_get_args());
    if (isset(self::$producers[$key])) {
      return self::$producers[$key];
    }

    $producer_class = 'Producer' . FoundationString::className($producer_name);
    if (!class_exists($producer_class)) {
      throw new \InvalidArgumentException(sprintf('Класс продюсера "%s" не определен', $producer_class));
    }

    $serializer = SerializerFactory::get($serializer_name);

    $producer = new $producer_class(FoundationKafka::brokers(), $id);
    $producer->setSerializer($serializer);
    self::$producers[$key] = $producer;

    return $producer;
  }

}
