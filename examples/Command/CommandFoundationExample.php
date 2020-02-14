<?php

/**
 * Пример класса команды.
 */
class CommandFoundationExample extends CommandPrototype {

  /**
   * Приветствие.
   *
   * @inheritdoc
   */
  public function greeting() {
    return 'Foundation example';
  }

  /**
   * Использование команды.
   *
   * @inheritdoc
   */
  public function usage() {
    return 'Usage, php command.php foundation-example "arg1" "arg2 (optional)"';
  }

  /**
   * Валидация команды.
   *
   * @inheritdoc
   */
  public function validate() {
    return !empty($this->args);
  }

  /**
   * Процессинг команды.
   *
   * @inheritdoc
   */
  public function handle() {
    $started = time();
    $this->stdout('Start', date('d.m.Y H:i:s', $started));
    $this->stdout('Produce', $this->args);

    $topic = 'foundation_example';
    $message = array(
      'scheme' => 'foundation_example',
      'payload' => array(
        'type' => 'example',
        'timestamp' => time(),
        'message' => drupal_json_encode($this->args),
      ),
    );
    $producer = ProducerFactory::get();
    $producer->produce($topic, $message);
    $producer->flush();

    $this->stdout('Finish', date('d.m.Y H:i:s', time()));
    $this->stdout('Elapsed (min)', (time() - $started) / 60);

    return $this;
  }

}
