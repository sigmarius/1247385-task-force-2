<?php
require_once 'vendor/autoload.php';

use Taskforce\Service;
use Taskforce\Exceptions\SourceFileException;
use Taskforce\Exceptions\FileFormatException;

$categoriesLoader = new Service\SqlFromCsvCreater(
    '/data/categories.csv',
    ['name', 'icon']
);

$citiesLoader = new Service\SqlFromCsvCreater(
    '/data/cities.csv',
    ['name', 'latitude', 'longitude']
);

try {
    $categoriesLoader->createSqlFile();
    $citiesLoader->createSqlFile();
} catch (SourceFileException $e) {
    echo "Не удалось обработать csv файл: " . $e->getMessage();
} catch (FileFormatException $e) {
    echo "Неверное расширение файла: " . $e->getMessage();
}
echo 'done!';
