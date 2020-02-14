<?php

/**
 * Государственная услуга (ИНН ФЛ).
 */
class SmevRequestFnsFl extends SmevRequestPrototype {

  /**
   * Отправка запроса.
   *
   * @inheritDoc
   */
  public function post($id, array $data) {
    $request = array(
      'systemId' => '1',
      'querytype' => 'VS00050v003',
      "uuid" => $id,
      "timestamp" => date('Y-m-d\TH:i:s.uP'),
      'FNSINNSingularRequest' => array(
        'SvedUl' => array(
          'OrgName' => $data['org']['name'],
          'InnUl' => $data['org']['inn'],
          'OGRN' => $data['org']['ogrn'],
        ),
        'SvedFl' => array(
          'FIO' => array(
            'surname' => $data['fio']['surname'],
            'name' => $data['fio']['name'],
            'middlename' => $data['fio']['middlename'],
          ),
          'FlDoc' => array(
            'DocCode' => $data['doc']['DocCode'],
            'DocSerNum' => $data['doc']['DocSerNum'],
            'DocDate' => $data['doc']['DocDate'],
            'DocIss' => $data['doc']['DocIss'],
            'DocIssCode' => $data['doc']['DocIssCode'],
          ),
          'BirthDate' => $data['fio']['BirthDate'],
          'BirthPlace' => $data['fio']['BirthPlace'],
        ),
        'QueryId' => $id,
      )
    );

    if ($this->topic && $this->scheme) {
      FoundationKafka::avro($this->topic, $this->scheme, $request, $this->key);
    }

    return $request;
  }

}
