<?php

/**
 * Интерфейс.
 */
interface SmevRequestInterface {

  /**
   * Сеттер топика.
   *
   * @param string $name
   *   Имя.
   *
   * @return self
   *   Текущий объект.
   */
  public function topic($name);

  /**
   * Сеттер схемы.
   *
   * @param string $name
   *   Имя.
   *
   * @return self
   *   Текущий объект.
   */
  public function scheme($name);

  /**
   * Сеттер ключа.
   *
   * @param string $name
   *   Имя.
   *
   * @return self
   *   Текущий объект.
   */
  public function key($name);

  /**
   * Отправка запроса.
   *
   * @param string $id
   *   Идентификатор запроса.
   * @param array $data
   *   Данные запроса.
   *
   * @return mixed
   *   Данные.
   */
  public function post($id, array $data);

}
