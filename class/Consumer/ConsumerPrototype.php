<?php

use RdKafka\Message;
use RdKafka\KafkaConsumer;

/**
 * Прототип консьюмера.
 */
abstract class ConsumerPrototype implements ConsumerInterface {
  protected $kafka;
  protected $id;
  protected $topics;
  protected $type;
  protected $exception;

  /**
   * Геттер идентификатора.
   *
   * @inheritdoc
   */
  public function id() {
    return $this->id;
  }

  /**
   * Геттер топиков.
   *
   * @inheritdoc
   */
  public function topic() {
    return $this->topics;
  }

  /**
   * Геттер идентификатора.
   *
   * @inheritdoc
   */
  public function topicConf() {
    return NULL;
  }

  /**
   * Сеттер id консьюмера.
   *
   * @inheritdoc
   */
  public function setId($id) {
    $this->id = $id;
    return $this;
  }

  /**
   * Сеттер топиков.
   *
   * @inheritdoc
   */
  public function setTopics($topics) {
    $this->topics = $topics;
    return $this;
  }

  /**
   * Сеттер кафки.
   *
   * @inheritdoc
   */
  public function setKafka(KafkaConsumer $kafka) {
    $this->kafka = $kafka;
    return $this;
  }

  /**
   * Геттер исключения.
   *
   * @inheritdoc
   */
  public function getException() {
    return $this->exception;
  }

  /**
   * Обработка события.
   *
   * @inheritdoc
   */
  abstract public function consume(Message $message);

}
