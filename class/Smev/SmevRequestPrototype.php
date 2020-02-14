<?php

/**
 * Класс запроса.
 */
abstract class SmevRequestPrototype implements SmevRequestInterface {
  protected $topic;
  protected $scheme;
  protected $key;

  /**
   * Сеттер топика.
   *
   * @inheritDoc
   */
  public function topic($name) {
    $this->topic = $name;
    return $this;
  }

  /**
   * Сеттер схемы.
   *
   * @inheritDoc
   */
  public function scheme($name) {
    $this->scheme = $name;
    return $this;
  }

  /**
   * Сеттер ключа.
   *
   * @inheritDoc
   */
  public function key($name) {
    $this->key = $name;
    return $this;
  }

}
