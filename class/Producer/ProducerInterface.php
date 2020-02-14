<?php

/**
 * Интерфейс продюсера.
 */
interface ProducerInterface {

  /**
   * Сеттер сериализатора.
   *
   * @param SerializerInterface $serializer
   *   Объект сериализатора.
   *
   * @return self
   *   Текущий объект.
   */
  public function setSerializer(SerializerInterface $serializer);

  /**
   * Сеттер длительности поллинга.
   *
   * @param int $ms
   *   Задержка.
   *
   * @return self
   *   Текущий объект.
   */
  public function setPoll($ms);

  /**
   * Сеттер обработчика.
   *
   * @param callable $callback
   *   Обработчик.
   *
   * @return self
   *   Текущий объект.
   */
  public function setDelivery(callable $callback);

  /**
   * Сеттер значения конфа.
   *
   * @param string $key
   *   Ключ.
   * @param mixed $value
   *   Значение.
   *
   * @return self
   *   Текущий объект.
   */
  public function addItem($key, $value);

  /**
   * Продюсе сообщения.
   *
   * @param string $name
   *   Имя топика.
   * @param mixed $message
   *   Сообщение.
   * @param string $key
   *   Ключ.
   * @param int $partition
   *   Партиция.
   * @param array $data
   *   Конфиг топика.
   *
   * @return self
   *   Текущий объект.
   */
  public function produce($name, $message, $key = NULL, $partition = RD_KAFKA_PARTITION_UA, array $data = array());

  /**
   * Поллинг ответов.
   *
   * @param int $ms
   *   Задержка.
   *
   * @return self
   *   Текущий объект.
   */
  public function poll($ms);

  /**
   * Фетч сообщений продюсера.
   */
  public function flush($exception_callback = NULL);

}
