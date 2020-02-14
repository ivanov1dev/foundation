Foundation
==

Установка расширений
--
[php-rdkafka](https://github.com/arnaud-lb/php-rdkafka)

Установка модуля
--
Скопировать модуль в проект  
```shell script
cd /tmp
git clone ..foundation.git
rm -rf foundation/.git
mkdir sites/all/modules/third
mv foundation sites/all/modules/third
``` 

Перенести файлы command.php и composer.json из каталога модуля в корневой каталог 
```shell script
mv sites/all/modules/third/foundation/command.php .
mv sites/all/modules/third/foundation/command.json .
``` 

Загрузить зависимости
```shell script
composer install -vvv
```

В index.php добавить скрипт автозагрузки
```php
require_once DRUPAL_ROOT . '/vendor/autoload.php';
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
```

Включить модуль
```shell script
drush en foundation -y
```

Настройка Kafka и Avro Registry Scheme
--
admin/config/development/kafka 

Настройка Camunda
--
admin/config/development/camunda

Запуск команд
--

```shell script
php command.php foundation-example foo bar
```


