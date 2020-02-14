<?php

/**
 * Интерфейс продюсера.
 */
interface SerializerInterface {

  /**
   * Сериализация.
   *
   * @param mixed $message
   *   Сообщение.
   *
   * @return mixed
   *   Результат сериализации.
   */
  public function serialize($message);

  /**
   * Десериализация.
   *
   * @param mixed $message
   *   Сообщение.
   * @param mixed $scheme
   *   Схема.
   *
   * @return mixed
   *   Результат десериализация.
   */
  public function deserialize($message, &$scheme = NULL);

}
