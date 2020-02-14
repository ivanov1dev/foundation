<?php

/**
 * Прототип класс команды.
 */
abstract class CommandPrototype implements CommandInterface {
  protected $args;
  protected $errors;
  protected $validateErrors;
  protected $pid;

  /**
   * Запуск команды.
   *
   * @inheritdoc
   */
  public function __construct() {
    $this->errors = array();
    $this->validateErrors = array();
    $this->pid = getmypid();
  }

  /**
   * Приветствие.
   *
   * @inheritdoc
   */
  abstract public function greeting();

  /**
   * Использование команды.
   *
   * @inheritdoc
   */
  abstract public function usage();

  /**
   * Запуск команды.
   *
   * @inheritdoc
   */
  public function run() {
    $this->stdout($this->greeting());

    if ($this->validate()) {
      try {
        $this->handle();
      }
      catch (\Exception $e) {
        $this->stdout($e->getMessage());
      }
    }
    else {
      $this->stdout($this->usage());

      foreach ($this->validateErrors as $error) {
        $this->stdout('Error', $error);
      }
    }
    return $this;
  }

  /**
   * Валидация команды.
   *
   * @inheritdoc
   */
  public function validate() {
    return empty($this->validateErrors);
  }

  /**
   * Процессинг команды.
   *
   * @inheritdoc
   */
  abstract public function handle();

  /**
   * Маппинг агрументов.
   *
   * @inheritdoc
   */
  public function args(array $args) {
    $this->args = $args;
    return $this;
  }

  /**
   * Вывод команды.
   *
   * @return self
   *   Текущий объект.
   */
  protected function stdout() {
    $args = func_get_args();
    foreach ($args as &$arg) {
      if (!is_scalar($arg)) {
        $arg = drupal_json_encode($arg);
      }
    }

    $args = array_merge(array($this->pid, date('d.m.Y H:i:s')), $args);
    fwrite(STDOUT, implode("\t", $args) . PHP_EOL);

    return $this;
  }

}
