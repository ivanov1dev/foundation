<?php

use RdKafka\Conf;
use RdKafka\Message;
use RdKafka\Producer;
use RdKafka\TopicConf;

/**
 * Класс продюсера (базовый).
 */
class ProducerDefault implements ProducerInterface {
  const POLL_MS = 50;

  protected $brokers;
  protected $producer;
  protected $topics;
  protected $errorCallback;
  protected $deliveryCallback;
  protected $data;
  protected $logLevel;
  protected $poll;
  protected $serializer;

  /**
   * Конструктор класса.
   *
   * @param string $brokers
   *   Брокеры кафки.
   * @param string $id
   *   Идентификатор клиента.
   */
  public function __construct($brokers, $id) {
    $this->id = $id;
    $this->brokers = $brokers;
    $this->logLevel = LOG_DEBUG;
    $this->data = array();
    $this->poll = self::POLL_MS;
    $this->serializer = NULL;
  }

  /**
   * Сеттер сериализатора.
   *
   * @inheritdoc
   */
  public function setSerializer(SerializerInterface $serializer) {
    $this->serializer = $serializer;
    return $this;
  }

  /**
   * Сеттер длительности поллинга.
   *
   * @inheritdoc
   */
  public function setPoll($ms) {
    $this->poll = $ms;
    return $this;
  }

  /**
   * Сеттер обработчика.
   *
   * @inheritdoc
   */
  public function setDelivery(callable $callback) {
    $this->delivery = $callback;
    return $this;
  }

  /**
   * Сеттер значения конфа.
   *
   * @inheritdoc
   */
  public function addItem($key, $value) {
    $this->data[$key] = $value;
    return $this;
  }

  /**
   * Отправка сообщения.
   *
   * @inheritdoc
   */
  public function produce($name, $message, $key = NULL, $partition = RD_KAFKA_PARTITION_UA, array $data = array()) {
    if (!$this->serializer) {
      throw new RuntimeException('Сериалайзер для продюсера не определен.');
    }

    $producer = $this->getProducer();

    if (empty($this->topics[$name])) {
      $conf = new TopicConf();
      foreach ($data as $key => $value) {
        $conf->set($key, $value);
      }
      $this->topics[$name] = $producer->newTopic($name, $conf);
    }

    $topic = $this->topics[$name];

    $topic->produce($partition, 0, $this->serializer->serialize($message), $key);
    return $this;
  }

  /**
   * Поллинг сообщения.
   *
   * @inheritdoc
   */
  public function poll($ms) {
    $this->getProducer()->poll($ms);
    return $this;
  }

  /**
   * Фетч сообщений продюсера.
   *
   * @inheritDoc
   */
  public function flush($exception_callback = NULL) {
    $producer = $this->getProducer();

    while ($producer->getOutQLen() > 0) {
      try {
        $producer->poll(1);
      }
      catch (\Exception $e) {
        if (is_callable($exception_callback)) {
          $exception_callback($e->getMessage());
        }
      }
    }
  }

  /**
   * Билдер продюсера.
   *
   * @return RdKafka\Producer
   *   Объект продюссера.
   */
  private function getProducer() {
    if ($this->producer) {
      return $this->producer;
    }

    $conf = new Conf();
    $conf->set('client.id', $this->id);

    $login = FoundationKafka::login();
    $password = FoundationKafka::password();
    if ($login && $password) {
      $conf->set('security.protocol', 'sasl_plaintext');
      $conf->set('sasl.mechanisms', 'PLAIN');
      $conf->set('sasl.username', $login);
      $conf->set('sasl.password', $password);
    }

    if ($this->poll) {
      $errorCallback = $this->errorCallback;
      if (is_null($errorCallback)) {
        $errorCallback = static function (Producer $kafka, $err, $reason) {
          throw new Exception(sprintf('%s. %s', rd_kafka_err2str($err), $reason));
        };
      }
      $conf->setErrorCb($errorCallback);

      $deliveryCallback = $this->deliveryCallback;
      if (is_null($deliveryCallback)) {
        $deliveryCallback = static function (Producer $kafka, Message $message) {
          if ($message->err) {
            throw new Exception(sprintf('%d. %s', $message->err, $message->errstr()));
          }
        };
      }
      $conf->setDrMsgCb($deliveryCallback);
    }

    foreach ($this->data as $key => $value) {
      $conf->set($key, $value);
    }

    $this->producer = new Producer($conf);
    $this->producer->addBrokers($this->brokers);
    return $this->producer;
  }

}
