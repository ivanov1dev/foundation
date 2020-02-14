<?php

/**
 * Класс упраления.
 */
class FoundationKafka {

  /**
   * Геттер брокеров.
   *
   * @return string
   *   Массив активных плагинов.
   */
  public static function brokers() {
    return variable_get('kafka_brokers', '');
  }

  /**
   * Геттер логина.
   *
   * @return string
   *   Логин.
   */
  public static function login() {
    return variable_get('kafka_login', '');
  }

  /**
   * Геттер пароля.
   *
   * @return string
   *   Пароль.
   */
  public static function password() {
    return variable_get('kafka_password', '');
  }

  /**
   * Продюссинг.
   *
   * @param string $topic
   *   Наименование топика.
   * @param array $payload
   *   Полезная нагрузка.
   * @param mixed $key
   *   Ключ.
   *
   * @return ProducerInterface
   *   Объект продюсера.
   */
  public static function json($topic, array $payload, $key = '') {
    return ProducerFactory::get($key, 'json')->produce($topic, $payload, $key);
  }

  /**
   * Продюссинг AVRO.
   *
   * @param string $topic
   *   Наименование топика.
   * @param mixed $scheme
   *   Наменование схемы.
   * @param mixed $payload
   *   Полезная нагрузка.
   * @param mixed $key
   *   Ключ.
   *
   * @return ProducerInterface
   *   Объект продюсера.
   */
  public static function avro($topic, $scheme, $payload, $key = '') {
    return ProducerFactory::get($key, 'avro')->produce($topic, array('scheme' => $scheme, 'payload' => $payload), $key);
  }

}
