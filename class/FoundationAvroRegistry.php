<?php

/**
 * Класс упраления.
 */
class FoundationAvroRegistry {

  /**
   * Геттер хоста реестра.
   *
   * @return string
   *   Хост.
   */
  public static function registry() {
    return variable_get('avro_registry_host', '');
  }

  /**
   * Геттер хоста конфльента.
   *
   * @return string
   *   Хост.
   */
  public static function confluent() {
    return variable_get('avro_confluent_host', '');
  }

}
