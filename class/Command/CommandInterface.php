<?php

/**
 * Интерфейс команды.
 */
interface CommandInterface {

  /**
   * Приветствие.
   *
   * @return string
   *   Строка вывода.
   */
  public function greeting();

  /**
   * Использование команды.
   *
   * @return string
   *   Строка вывода.
   */
  public function usage();

  /**
   * Запуск команды.
   *
   * @return self
   *   Текущий объект.
   */
  public function run();

  /**
   * Валидация команды.
   *
   * @return bool
   *   Результат валидации.
   */
  public function validate();

  /**
   * Процессинг команды.
   *
   * @return self
   *   Текущий объект.
   */
  public function handle();

  /**
   * Маппинг агрументов.
   *
   * @param array $args
   *   Массив аргументов.
   *
   * @return self
   *   Текущий объект.
   */
  public function args(array $args);

}
