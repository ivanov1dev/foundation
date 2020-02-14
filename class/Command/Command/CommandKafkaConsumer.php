<?php

use RdKafka\Conf;
use RdKafka\TopicConf;
use RdKafka\KafkaConsumer;

/**
 * Команда ранер консьюмера.
 */
class CommandKafkaConsumer extends CommandPrototype implements CommandInterface {
  const CONSUMER_TIMEOUT = 5000;
  const CONSUMER_LOG_TIMEOUT = 60;

  protected $name;
  protected $id;
  protected $topics;

  protected $lastLogging;

  /**
   * Приветствие.
   *
   * @inheritdoc
   */
  public function greeting() {
    return 'Kafka consumer';
  }

  /**
   * Использование команды.
   *
   * @inheritdoc
   */
  public function usage() {
    return 'Usage, php command.php kafka-consumer "name" "id" "topics"';
  }

  /**
   * Маппинг агрументов.
   *
   * @inheritdoc
   */
  public function args(array $args) {
    $this->name = isset($args[0]) ? $args[0] : 'unknown';
    $this->id = isset($args[1]) ? $args[1] : NULL;
    $this->topics = isset($args[2]) ? explode(',', $args[2]) : NULL;
    return $this;
  }

  /**
   * Валидация команды.
   *
   * @inheritdoc
   */
  public function validate() {
    return !empty($this->name);
  }

  /**
   * Процессинг команды.
   *
   * @inheritdoc
   * @throws \RdKafka\Exception
   */
  public function handle() {
    $consumer = ConsumerFactory::get($this->name);

    if ($this->id) {
      $consumer->setId($this->id);
    }
    if ($this->topics) {
      $consumer->setTopics($this->topics);
    }

    // подготовка конфигурации топика
    if (!$topicConf = $consumer->topicConf()) {
      $topicConf = new TopicConf();
      $topicConf->set('auto.offset.reset', 'smallest');
    }

    // подготовка конфигурации
    $conf = new Conf();
    $conf->setRebalanceCb(function (KafkaConsumer $kafka, $err, array $partitions = NULL) {
      switch ($err) {
        case RD_KAFKA_RESP_ERR__ASSIGN_PARTITIONS:
          $kafka->assign($partitions);
          break;

        case RD_KAFKA_RESP_ERR__REVOKE_PARTITIONS:
          $kafka->assign(NULL);
          break;

        default:
          $kafka->assign(NULL);
          throw new \Exception($err);
      }
    });

    if (!$id = $consumer->id()) {
      throw new InvalidArgumentException('Идентификатор консьюмера не определен.');
    }
    if (!$topics = $consumer->topic()) {
      throw new InvalidArgumentException('Топики консьюмера не определены.');
    }

    $conf->set('group.id', $id);
    $conf->set('metadata.broker.list', FoundationKafka::brokers());
    $conf->set('enable.auto.commit', 'false');

    $login = FoundationKafka::login();
    $password = FoundationKafka::password();
    if ($login && $password) {
      $conf->set('security.protocol', 'sasl_plaintext');
      $conf->set('sasl.mechanisms', 'PLAIN');
      $conf->set('sasl.username', $login);
      $conf->set('sasl.password', $password);
    }

    $conf->setDefaultTopicConf($topicConf);

    $kafka = new KafkaConsumer($conf);
    $kafka->subscribe($topics);

    $consumer->setKafka($kafka);

    while (TRUE) {
      try {
        $message = $kafka->consume($this->consumeTimeout());

        switch ($message->err) {
          case RD_KAFKA_RESP_ERR_NO_ERROR:
            $this->stdout($this->name, $message->payload);

            $consumer->consume($message);
            if ($e = $consumer->getException()) {
              $this->stdout($this->name, $e->getMessage());
            }
            break;

          case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            $this->stdout($this->name, 'No more messages; will wait for more');
            $this->resetCache();
            break;

          case RD_KAFKA_RESP_ERR__TIMED_OUT:
            if ((time() - $this->lastLogging) >= $this->logTimeout()) {
              $this->lastLogging = time();
              $this->stdout($this->name, 'Wait');
              $this->resetCache();
            }
            break;

          default:
            $this->stdout($message->errstr(), $message->err);
            break;
        }
      }
      catch (Exception $e) {
        $this->stdout($e->getMessage());
      }
    }
  }

  /**
   * Геттер таймаута.
   *
   * @return int
   *   Значаение таймаута.
   */
  protected function consumeTimeout() {
    return self::CONSUMER_TIMEOUT;
  }

  /**
   * Геттер таймаута (лог).
   *
   * @return int
   *   Значаение таймаута.
   */
  protected function logTimeout() {
    return self::CONSUMER_TIMEOUT;
  }

  /**
   * Сброс кэшей.
   */
  protected function resetCache() {
    entity_get_controller('node')->resetCache();
    entity_get_controller('field_collection_item')->resetCache();
  }

}
