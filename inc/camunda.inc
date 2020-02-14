<?php

/**
 * Форма настроек.
 */
function foundation_camunda_settings_form($form, &$form_state) {
  $form = array();
  $form['camunda'] = array(
    '#type' => 'fieldset',
    '#title' => 'Хосты',
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['camunda']['camunda_host'] = array(
    '#type' => 'textfield',
    '#title' => 'Хост',
    '#default_value' => variable_get('camunda_host', ''),
  );
  $form['schemes'] = array(
    '#type' => 'fieldset',
    '#title' => 'Топики и схемы',
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['schemes']['camunda_kafka_start'] = array(
    '#type' => 'textfield',
    '#title' => 'Топик для запуска бизнес-процессов',
    '#default_value' => variable_get('camunda_kafka_start', ''),
  );
  $form['schemes']['camunda_kafka_start_scheme'] = array(
    '#type' => 'textfield',
    '#title' => 'Схема для запуска бизнес-процессов',
    '#default_value' => variable_get('camunda_kafka_start_scheme', ''),
  );
  $form['schemes']['camunda_kafka_request'] = array(
    '#type' => 'textfield',
    '#title' => 'Топик для запросов',
    '#default_value' => variable_get('camunda_kafka_request', ''),
  );
  $form['schemes']['camunda_kafka_request_scheme'] = array(
    '#type' => 'textfield',
    '#title' => 'Схема для запросов',
    '#default_value' => variable_get('camunda_kafka_request_scheme', ''),
  );
  $form['schemes']['camunda_kafka_response'] = array(
    '#type' => 'textfield',
    '#title' => 'Топик для ответов',
    '#default_value' => variable_get('camunda_kafka_response', ''),
  );
  $form['schemes']['camunda_kafka_response_scheme'] = array(
    '#type' => 'textfield',
    '#title' => 'Схема для ответов',
    '#default_value' => variable_get('camunda_kafka_response_scheme', ''),
  );
  return system_settings_form($form);
}
