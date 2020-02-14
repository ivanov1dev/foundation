<?php

use RdKafka\Message;

/**
 * Класс консьюмера.
 */
class ConsumerFoundationExample extends ConsumerPrototype {
  private $serializer;

  /**
   * Конструктор класса.
   *
   * @inheritdoc
   */
  public function __construct() {
    $this->serializer = SerializerFactory::get('avro');
  }

  /**
   * Обработка события.
   *
   * @inheritdoc
   */
  public function consume(Message $message) {
    try {
      $payload = $this->serializer->deserialize($message);

      if ($payload['type'] == 'example') {
        $topic = 'foundation_example';
        $message = array(
          'scheme' => 'foundation_example',
          'payload' => array(
            'type' => 'example',
            'timestamp' => time(),
            'message' => drupal_json_encode($payload),
          )
        );

        $producer = ProducerFactory::get();
        $producer->produce($topic, $message);
        // флуш обязателен после всех produce (кафка отвечает асинхронно и по сути это ожидание ее ответов)
        // если не делать, то возможны исключения, когда ответы кафки приходят в скрипт в момент завершения его работы.
        $producer->flush();
      }
    }
    catch (Exception $e) {
      // обработка исключений
      // если необходима строгость обработки пакетов,
      // то надо перенести в эту область $this->kafka->commit($message)
    }

    $this->kafka->commit($message);
  }

  /**
   * Валидация пакета.
   *
   * @param mixed $payload
   *   Пакет данных.
   *
   * @return bool
   *   Результат проверки
   */
  protected function validate($payload) {
    return
      isset($payload['source']) && isset($payload['destination']) &&
      isset($payload['service']) && isset($payload['operation']) &&
      isset($payload['request']) && isset($payload['files']) &&
      ($payload['source'] == 'docflow') && ($payload['destination'] == 'registry');
  }
}
