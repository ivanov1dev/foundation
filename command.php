<?php

define('DRUPAL_ROOT', realpath(dirname(__FILE__)));
chdir(DRUPAL_ROOT);

require_once DRUPAL_ROOT . '/vendor/autoload.php';
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';

$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_USER_AGENT'] = 'Drupal Command';

drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

try {
  CommandFactory::get()->run();
}
catch (\InvalidArgumentException $e) {
  print $e->getMessage() . PHP_EOL;
}
