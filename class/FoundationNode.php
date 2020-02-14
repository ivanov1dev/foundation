<?php

/**
 * Класс управления нодами.
 */
class FoundationNode {

  /**
   * Геттер UUID.
   *
   * @param int $id
   *   Идентификатор ноды.
   *
   * @return string|null
   *   UUID идентификатор.
   */
  public static function uuid($id) {
    $uuid = NULL;

    if ($uuids = entity_get_uuid_by_id('node', array($id))) {
      $uuid = reset($uuids);
    }

    return $uuid;
  }

  /**
   * Загрузка заголовка ноды.
   *
   * @param int $nid
   *   Идентификатор ноды.
   *
   * @return string
   *   Заголовок ноды.
   */
  public static function title($nid) {
    $title = self::property($nid, 'title');
    return $title ? $title : 'Без названия';
  }

  /**
   * Загрузка заголовка ноды.
   *
   * @param array $nids
   *   Идентификаторы нод.
   *
   * @return array
   *   Заголовки нод.
   */
  public static function titleMultiple(array $nids) {
    $query = db_select('node', 'n');
    $query->addField('n', 'nid');
    $query->addField('n', 'title');
    $query->condition('n.nid', $nids);
    return $query->execute()->fetchAllKeyed();
  }

  /**
   * Загрузка поля ноды.
   *
   * @param int $nid
   *   Идентификатор ноды.
   * @param string $property
   *   Поле ноды.
   *
   * @return string
   *   Значение поля.
   */
  public static function property($nid, $property) {
    $result = NULL;

    $query = db_select('node', 'n');
    $query->addField('n', $property);
    $query->condition('n.nid', $nid);

    return $query->execute()->fetchField();
  }

  /**
   * Загрузка полей нод.
   *
   * @param array $nids
   *   Идентификаторы нод.
   * @param array $properties
   *   Поля нод.
   *
   * @return array
   *   Массив данных.
   */
  public static function propertyMultiple(array $nids, array $properties) {
    $result = array();

    $query = db_select('node', 'n');
    foreach ($properties as $value) {
      $alias = NULL;
      $property = $value;

      if (is_array($value)) {
        $alias = key($value);
        $property = reset($value);
      }

      $query->addField('n', $property, $alias);
    }
    $query->condition('n.nid', $nids, 'IN');
    $query_result = $query->execute();

    while ($record = $query_result->fetchAssoc()) {
      $result[] = $record;
    }

    return $result;
  }

  /**
   * Загрузка ноды по заголовку.
   *
   * @param string $title
   *   Заголовок ноды.
   * @param string $type
   *   (опционально) Тип ноды.
   *
   * @return object
   *   Объект ноды.
   */
  public static function loadByTitle($title, $type = NULL) {
    $result = NULL;

    $query = db_select('node', 'n');
    $query->addField('n', 'nid');
    $query->condition('n.title', $title);
    if ($type) {
      $query->condition('n.type', $type);
    }

    if ($nid = $query->execute()->fetchField()) {
      $result = node_load($nid);
    }

    return $result;
  }

  /**
   * Ссылка на ноду.
   *
   * @param object $node
   *   Данные сущности.
   * @param array $options
   *   (опционально) Массив опций.
   *
   * @return string
   *   Объект сущности.
   */
  public static function link($node, array $options = array()) {
    $options += array(
      'target' => NULL,
      'title' => $node->title,
      'attributes' => array(),
      'query' => array(),
    );

    // подготовка назначения
    if ($options['target']) {
      $options['attributes']['target'] = $options['target'];
    }

    $title = $node->title;
    if (!empty($options['title'])) {
      $title = $options['title'];
    }

    return l($title, 'node/' . $node->nid, array(
      'attributes' => $options['attributes'],
      'query' => $options['query'],
    ));
  }

  /**
   * Ссылка на ноду по идентификатору.
   *
   * @param int $nid
   *   Идентификатор ноды.
   * @param array $options
   *   Массив опций.
   *
   * @return string
   *   Объект сущности.
   */
  public static function linkById($nid, array $options = array()) {
    return self::link(node_load($nid), $options);
  }

  /**
   * Выгрузка полей ноды.
   *
   * @param object $node
   *   Данные сущности.
   *
   * @return array
   *   Массив полей.
   */
  public static function fieldValues($node) {
    $result = array();

    $properties = array(
      'nid', 'vid', 'uid', 'uuid', 'vuuid', 'title', 'created', 'changed',
      'workunit', 'orgunit', 'orgunit_affiliate', 'revision_uid', 'revision_timestamp',
    );
    foreach ($properties as $property) {
      if (isset($node->{$property})) {
        $result[$property] = $property;
      }
    }

    $instances = field_info_instances('node', $node->type);
    foreach ($instances as $name => $instance) {
      if (isset($node->{$instance['field_name']})) {
        $result[$instance['field_name']] = $node->{$instance['field_name']};
      }
    }

    return $result;
  }

  /**
   * Заголовок с номером версии.
   *
   * @param object $node
   *   Объект ноды.
   * @param array $revisions
   *   (опционально) Массив ревизий.
   *
   * @return string
   *   Текст заголовка.
   */
  public static function titleWithRevision($node, array $revisions = array()) {
    if (!$result = drupal_get_title()) {
      $result = check_plain($node->title);
    }

    if (!$revisions) {
      $revisions = node_revision_list($node);
    }

    if (!$revisions) {
      $index = array_search($node->vid, array_reverse(array_keys($revisions)));
      $result = sprintf('%s (вер. %d)', $result, $index + 1);
    }

    return $result;
  }

  /**
   * Массив данных полей по бэндлам.
   *
   * @param array $bundles
   *   Массив типов нод.
   *
   * @return array
   *   Массив данных моделй.
   */
  public static function getFieldsByBundles(array $bundles) {
    $output = array();

    if (empty($bundles['base_doc'])) {
      $bundles[] = 'base_doc';
    }

    // получить список бэндлов
    $field_bundles = field_info_bundles();

    foreach ($bundles as $bundle) {
      // получить список полей бэндла
      $instances = field_info_instances('node', $bundle);

      // пройти поля
      foreach ($instances as $field_name => $field) {
        $output[$field_name]['field'] = $field_name;
        $output[$field_name]['title'] = $field['label'];

        // заполнить список бэндлов
        $admin_path = _field_ui_bundle_admin_path('node', $bundle);
        $output[$field_name]['bundles'][] = $admin_path ? l($field_bundles['node'][$bundle]['label'], $admin_path . '/fields') : $field_bundles['node'][$bundle]['label'];
      }
    }

    if (!empty($output)) {
      foreach ($output as $field_name => $cell) {
        $output[$field_name]['bundles'] = implode(', ', $cell['bundles']);
      }
    }

    return $output;
  }

}
