<?php

/**
 * Класс запроса.
 */
class SmevRequestEgrip extends SmevRequestPrototype {

  /**
   * Отправка запроса.
   *
   * @inheritDoc
   */
  public function post($id, array $data) {
    $conditions = array(
      'ogrnip' => NULL,
      'inn' => NULL,
    );

    if (isset($data['ogrnip'])) {
      $conditions['ogrnip'] = $data['ogrnip'];
    }
    elseif (isset($data['inn'])) {
      $conditions['inn'] = $data['inn'];
    }

    $request = array(
      "systemId" => "1",
      "querytype" => "VS00050v003",
      "uuid" => $id,
      "timestamp" => date('Y-m-d\TH:i:s.uP'),
      "FNSVipIPRequest" => array(
        "nomerDela" => NULL,
        "idDoc" => isset($data['doc_id']) ? $data['doc_id'] : $id,
        "zaprosIP" => $conditions,
      ),
    );

    if ($this->topic && $this->scheme) {
      FoundationKafka::avro($this->topic, $this->scheme, $request, $this->key);
    }

    return $request;
  }

}
