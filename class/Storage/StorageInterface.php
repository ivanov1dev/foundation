<?php

/**
 * ИНтерфейс хранилища.
 */
interface StorageInterface {

  /**
   * Геттер объекта.
   *
   * @param string $uri
   *  URI объекта.
   *
   * @return string
   *   Содержимое объекта.
   */
  public function get($uri);

  /**
   * Геттер реального пути.
   *
   * @param string $uri
   *  URI объекта.
   * @param bool $prepare
   *  Воссоздание пути.
   *
   * @return string
   *   Полный путь до объекта.
   */
  public function realPath($uri, $prepare = FALSE);

  /**
   * Геттер URI.
   *
   * @param string $ns
   *  Неймспейс.
   * @param string $path
   *  Путь к хранилищу.
   *
   * @return string
   *   URI.
   */
  public function uri($ns, $path);

  /**
   * Сеттер объекта.
   *
   * @param string $uri
   *   URI объекта.
   * @param string $content
   *   Содержимое.
   *
   * @return string|null
   *   URI.
   */
  public function set($uri, $content);

  /**
   * Сеттер объекта.
   *
   * @param string $ns
   *   Неймспейс.
   * @param string $path
   *   Путь до объекта.
   * @param string $content
   *   Содержимое.
   *
   * @return string|null
   *   URI.
   */
  public function setNs($ns, $path, $content);

  /**
   * Сеттер объекта.
   *
   * @param string $uri
   *  URI объекта.
   * @param string $file_uri
   *   URI файла.
   *
   * @return string|null
   *   URI.
   */
  public function setFileUri($uri, $file_uri);

  /**
   * Сеттер объекта.
   *
   * @param string $ns
   *   Неймспейс.
   * @param string $path
   *   Путь до объекта.
   * @param string $file_uri
   *   URI файла.
   *
   * @return string|null
   *   URI.
   */
  public function setNsFileUri($ns, $path, $file_uri);

  /**
   * Сеттер файла.
   *
   * @param string $uri
   *  URI объекта.
   * @param object $file
   *   Объект файла.
   *
   * @return string|null
   *   URI.
   */
  public function setFile($uri, $file);

  /**
   * Сеттер файла.
   *
   *
   * @param string $ns
   *   Неймспейс.
   * @param string $path
   *   Путь до объекта.
   * @param object $file
   *   Объект файла.
   *
   * @return string|null
   *   URI.
   */
  public function setNsFile($ns, $path, $file);

  /**
   * Удаление объекта.
   *
   * @param string $uri
   *  URI объекта.
   *
   * @return StorageInterface
   *   Объект.
   */
  public function remove($uri);

}
