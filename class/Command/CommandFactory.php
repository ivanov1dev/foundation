<?php

/**
 * Фабрика комманд.
 */
final class CommandFactory {
  protected static $commands = array();

  /**
   * Геттер объекта команды.
   *
   * @param string $name
   *   Имя команды.
   * @param array $args
   *   Массив аргументов.
   *
   * @return CommandInterface
   *   Объект команды.
   */
  public static function get($name = NULL, array $args = array()) {
    if (!$name) {
      global $argc, $argv;

      if ($argc > 1) {
        $args = $argv;

        // удалить имя скрипта
        array_shift($args);
        // получить имя команды
        $name = array_shift($args);
      }
    }

    if (!$name) {
      throw new \InvalidArgumentException('Команда не определена.');
    }

    if (!isset(self::$commands[$name])) {
      $class_name = sprintf('Command%s', FoundationString::className($name));
      if (!class_exists($class_name)) {
        throw new \InvalidArgumentException(sprintf('Класс команды "%s" не определен.', $class_name));
      }

      self::$commands[$name] = new $class_name();
    }

    self::$commands[$name]->args($args);

    return self::$commands[$name];
  }

}
