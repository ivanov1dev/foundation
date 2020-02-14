<?php

/**
 * Интерфейс билдера.
 */
interface BuilderInterface {

  /**
   * Билд данных.
   *
   * @param array $data
   *   Конфиг топика.
   *
   * @return mixed
   *   Результат билда.
   */
  public function build(array $data);

}
