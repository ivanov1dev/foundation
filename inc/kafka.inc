<?php

/**
 * Форма настроек.
 */
function foundation_kafka_settings_form($form, &$form_state) {
  $form = array();
  $form['kafka'] = array(
    '#type' => 'fieldset',
    '#title' => 'Kafka',
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['kafka']['kafka_brokers'] = array(
    '#type' => 'textfield',
    '#title' => 'Брокеры',
    '#default_value' => variable_get('kafka_brokers', ''),
  );
  $form['kafka']['kafka_login'] = array(
    '#type' => 'textfield',
    '#title' => 'Логин',
    '#default_value' => variable_get('kafka_login', ''),
  );
  $form['kafka']['kafka_password'] = array(
    '#type' => 'textfield',
    '#title' => 'Пароль',
    '#default_value' => variable_get('kafka_password', ''),
  );
  $form['registry'] = array(
    '#type' => 'fieldset',
    '#title' => 'Kafka',
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['registry']['avro_registry_host'] = array(
    '#title' => 'Avro registry',
    '#type' => 'textfield',
    '#default_value' => variable_get('avro_registry_host', ''),
  );
  $form['registry']['avro_confluent_host'] = array(
    '#title' => 'Avro confluent',
    '#type' => 'textfield',
    '#default_value' => variable_get('avro_confluent_host', ''),
  );

  return system_settings_form($form);
}
