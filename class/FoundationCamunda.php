<?php

/**
 * Класс упраления.
 */
class FoundationCamunda {

  /**
   * Хост камунды.
   */
  public static function host() {
    return variable_get('camunda_host', '');
  }

  /**
   * Топик старта бизнес-процесса.
   */
  public static function kafkaStartTopic() {
    return variable_get('camunda_kafka_start', '');
  }

  /**
   * Схема старта бизнес-процесса.
   */
  public static function kafkaStartScheme() {
    return variable_get('camunda_kafka_start_scheme', '');
  }

  /**
   * Топик запроса.
   */
  public static function kafkaRequestTopic() {
    return variable_get('camunda_kafka_request', '');
  }

  /**
   * Схема запроса.
   */
  public static function kafkaRequestScheme() {
    return variable_get('camunda_kafka_start_request', '');
  }

  /**
   * Топик ответа.
   */
  public static function kafkaResponseTopic() {
    return variable_get('camunda_kafka_response', '');
  }

  /**
   * Схема ответа.
   */
  public static function kafkaResponseScheme() {
    return variable_get('camunda_kafka_response_scheme', '');
  }

  /**
   * Запуск бизнес-процесса.
   *
   * @param string $process_id
   *   Идентификатор процесса.
   * @param mixed $variables
   *   Набор переменных.
   */
  public static function start($process_id, $variables) {
    if (!$process_id) {
      return;
    }

    $message = array(
      'scheme' => self::kafkaStartScheme(),
      'payload' => array(
        'process_id' => $process_id,
        'variables' => drupal_json_encode($variables),
      )
    );

    $producer = ProducerFactory::get();
    $producer->produce(self::kafkaStartTopic(), $message, 'registry_drupal');
    $producer->flush();
  }

}
