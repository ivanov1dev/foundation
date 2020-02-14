<?php

/**
 * Класс сериализатора (AVRO).
 */
class SerializerAvro implements SerializerInterface {
  private $registryHost;
  private $confluentHost;
  private $schemaRegistry;

  /**
   * Конструктор класса.
   */
  public function __construct() {
    $this->registryHost = FoundationAvroRegistry::registry();
    $this->confluentHost = FoundationAvroRegistry::confluent();
    $this->schemaRegistry = new SchemaRegistry($this->registryHost, $this->confluentHost);
  }

  /**
   * Сериализация.
   *
   * @inheritdoc
   */
  public function serialize($message) {
    $result = NULL;
    if (empty($message['scheme'])) {
      throw new RuntimeException('Invalid avro scheme');
    }
    if (empty($message['payload']) || is_scalar($message['payload'])) {
      throw new RuntimeException('Invalid message payload');
    }

    $schema = $this->schemaRegistry->getByName($message['scheme']);
    $header = $this->schemaRegistry->getPacketHeaderFromCachedMeta($schema);

    $io = new StringIO();
    $dateWriter = new DataIOWriterSingleObjEnc($io, new IODatumWriter($schema), $schema, $header);
    $dateWriter->append($message['payload']);
    $dateWriter->close();
    return $io->string();
  }

  /**
   * Десериализация.
   *
   * @inheritdoc
   */
  public function deserialize($message, &$scheme = NULL) {
    $result = NULL;

    if (is_object($message)) {
      $message = (array) $message;
    }

    if (empty($message['payload'])) {
      throw new RuntimeException('Invalid message payload');
    }

    $io = new StringIO($message['payload']);
    $dataReader = new DataIOReaderSingleObjEnc($io, new IODatumReader(), $this->schemaRegistry);
    $result = $dataReader->data();
    $schemeInfo = drupal_json_decode($dataReader->getMetaDataFor('avro.schema'));
    $scheme = $schemeInfo['name'];
    return reset($result);
  }

}
