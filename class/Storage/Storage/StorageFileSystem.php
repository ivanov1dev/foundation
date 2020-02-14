<?php

/**
 * Класс хранилища.
 */
class StorageFileSystem implements StorageInterface {
  protected $basePath;

  /**
   * Конструктор класса.
   *
   * @param string $base_path
   *   Базовый путь.
   */
  public function __construct($base_path) {
    $this->basePath = file_default_scheme() . '://' . trim($base_path, '\/') . '/';
    $old = umask(0);
    if (!file_prepare_directory($this->basePath, FILE_MODIFY_PERMISSIONS | FILE_CREATE_DIRECTORY)) {
      throw new RuntimeException("Базовый путь \"{$this->basePath}\" хранилища не доступен на запись.");
    }
    umask($old);
  }

  /**
   * Геттер реального пути.
   *
   * @inheritDoc
   */
  public function realPath($uri, $prepare = FALSE) {
    list($ns, $path_local) = explode("://", $uri);
    $path_local = explode('/', $path_local);
    $file = end($path_local);
    $path_local = array_slice($path_local, 0, -1);

    $path = $this->basePath . implode('/', str_split(mb_substr($ns, 0, 8)));
    $path .= '/' . $ns;
    $path .= $path_local ? '/' . implode('/', $path_local) : '';
    $old = umask(0);
    if ($prepare && !file_prepare_directory($path, FILE_MODIFY_PERMISSIONS | FILE_CREATE_DIRECTORY)) {
      throw new RuntimeException("Путь хранилища \"{$path}\" не доступен на запись.");
    }
    umask($old);

    return $path . '/' . $file;
  }

  /**
   * Геттер URI.
   *
   * @inheritDoc
   */
  public function uri($ns, $path) {
    return "{$ns}://{$path}";
  }

  /**
   * Геттер объекта.
   *
   * @inheritDoc
   */
  public function get($uri) {
    $path = $this->realPath($uri);
    return is_readable($path) ? file_get_contents($path) : NULL;
  }

  /**
   * Сеттер объекта.
   *
   * @inheritDoc
   */
  public function set($uri, $content) {
    $old = umask(0);
    $result = file_put_contents($this->realPath($uri, TRUE), $content);
    umask($old);
    return $result ? $uri : NULL;
  }

  /**
   * Сеттер объекта.
   *
   * @inheritDoc
   */
  public function setNs($ns, $path, $content) {
    return $this->set($this->uri($ns, $path), $content);
  }

  /**
   * Сеттер объекта.
   *
   * @inheritDoc
   */
  public function setFileUri($uri, $file_uri) {
    $content = NULL;
    if (is_readable($file_uri)) {
      $content = file_get_contents($file_uri);
    }
    return $content ? $this->set($uri, $content) : NULL;
  }

  /**
   * Сеттер объекта.
   *
   * @inheritDoc
   */
  public function setNsFileUri($ns, $path, $file_uri) {
    return $this->setFileUri($this->uri($ns, $path), $file_uri);
  }

  /**
   * Сеттер файла.
   *
   * @inheritDoc
   */
  public function setFile($uri, $file) {
    $file_uri = is_array($file) ? $file['uri'] : $file->uri;
    return $file_uri ? $this->setFileUri($uri, $file_uri) : NULL;
  }

  /**
   * Сеттер файла.
   *
   * @inheritDoc
   */
  public function setNsFile($ns, $path, $file) {
    if (!$path) {
      $path = is_array($file) ? $file['filename'] : $file->filename;
    }

    return $this->setFile($this->uri($ns, $path), $file);
  }

  /**
   * Удаление объекта.
   *
   * @inheritDoc
   */
  public function remove($uri) {
    $path = $this->realPath($uri);
    if (is_readable($path)) {
      drupal_unlink($path);
    }
    return $this;
  }

}
