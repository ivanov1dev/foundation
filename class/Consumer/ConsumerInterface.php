<?php

use RdKafka\Message;
use RdKafka\KafkaConsumer;

/**
 * Интерфейс консьюмера.
 */
interface ConsumerInterface {

  /**
   * Геттер идентификатора.
   *
   * @return int
   *   Идентификатор.
   */
  public function id();

  /**
   * Геттер топиков.
   *
   * @return array
   *   Массив топиков.
   */
  public function topic();

  /**
   * Геттер идентификатора.
   *
   * @return mixed
   *   Конфиг топика.
   */
  public function topicConf();

  /**
   * Сеттер id консьюмера.
   *
   * @param string $id
   *   Идентификатор консьюмера.
   *
   * @return self
   *   Текущий объект.
   */
  public function setId($id);

  /**
   * Сеттер топиков.
   *
   * @param mixed $topics
   *   Топик или массив топиков.
   *
   * @return self
   *   Текущий объект.
   */
  public function setTopics($topics);

  /**
   * Сеттер кафки.
   *
   * @param RdKafka\KafkaConsumer $kafka
   *   Кафка.
   *
   * @return self
   *   Текущий объект.
   */
  public function setKafka(KafkaConsumer $kafka);

  /**
   * Геттер исключения.
   *
   * @return \Exception|null
   *   Текущий объект.
   */
  public function getException();

  /**
   * Обработка события.
   *
   * @param RdKafka\Message $message
   *   Сообщение.
   */
  public function consume(Message $message);

}
