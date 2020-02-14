<?php

/**
 * Класс сериализатора (json).
 */
class SerializerJson implements SerializerInterface {

  /**
   * Сериализация.
   *
   * @inheritdoc
   */
  public function serialize($message) {
    return is_string($message) ? $message : drupal_json_encode($message);
  }

  /**
   * Десериализация.
   *
   * @inheritdoc
   */
  public function deserialize($message, &$scheme = NULL) {
    $scheme = 'json';
    return drupal_json_decode($message);
  }

}
