<?php

/**
 * Класс запроса.
 */
class SmevRequestEgrul extends SmevRequestPrototype {

  /**
   * Отправка запроса.
   *
   * @param string $id
   *   Идентификатор запроса.
   * @param array $data
   *   Данные запроса.
   */
  public function post($id, array $data) {
    $conditions = array(
      'ogrn' => NULL,
      'innyul' => NULL,
    );
    if (isset($data['ogrn'])) {
      $conditions['ogrn'] = $data['ogrn'];
    }
    elseif (isset($data['inn'])) {
      $conditions['innyul'] = $data['inn'];
    }

    $request = array(
      "systemId" => "1",
      "querytype" => "VS00051v003",
      "uuid" => $id,
      "timestamp" => date('Y-m-d\TH:i:s.uP'),
      "FNSVipULRequest" => array(
        "nomerDela" => NULL,
        "idDoc" => isset($data['doc_id']) ? $data['doc_id'] : $id,
        "zaprosYUL" => $conditions,
      ),
    );

    if ($this->topic && $this->scheme) {
      FoundationKafka::avro($this->topic, $this->scheme, $request, $this->key);
    }
  }

}
