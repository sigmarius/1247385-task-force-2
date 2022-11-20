<?php
require_once 'vendor/autoload.php';

use Taskforce\Service;
use Taskforce\Exceptions\SourceFileException;
use Taskforce\Exceptions\FileFormatException;

$loader = new Service\SqlFromCsvCreater(
    '/data/categories.csv',
    ['name', 'icon']
);

try {
    $loader->createSqlFile();
} catch (SourceFileException $e) {
    error_log("Не удалось обработать csv файл: " . $e->getMessage());
} catch (FileFormatException $e) {
    error_log("Неверное расширение файла: " . $e->getMessage());
}
echo 'done!';
