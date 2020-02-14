<?php

/**
 * Команда консьюмера.
 */
class CommandFoundationConsumerExample extends CommandKafkaConsumer {

  /**
   * Приветствие.
   *
   * @inheritdoc
   */
  public function greeting() {
    return 'Foundation consumer';
  }

  /**
   * Использование команды.
   *
   * @inheritdoc
   */
  public function usage() {
    return 'Usage, php command.php foundation-consumer-example "topics (optional)" "id (optional)"';
  }

  /**
   * Маппинг агрументов.
   *
   * @inheritdoc
   */
  public function args(array $args) {
    // машинное имя консьюмера
    // совпадает с именем класса его реализации
    // <foundation> -> Consumer<Foundation>Example.php
    $this->name = 'foundation_example';
    // id консьюмера
    $this->id = isset($args[1]) ? $args[1] : 'foundation.consumer';
    // топики для наблюдения
    $this->topics = isset($args[0]) ? explode(',', $args[0]) : array('foundation_example');
    return $this;
  }

}
