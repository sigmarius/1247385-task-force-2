<?php

namespace Taskforce\Service\Helpers;

use Taskforce\Service\SqlFromCsvCreater;

class GenerateDataFromCsv
{
    protected array $file;

    public function __construct(array $file)
    {
        $this->file = $file;
    }

    public function makeArrays(): ?array
    {
        $loader = new SqlFromCsvCreater($this->file['path'], $this->file['columns']);
        $data = $loader->importCsv();

        $arrayValues = [];

        foreach ($this->file['columns'] as $id => $columnName) {
            $arrayValues[$columnName] = array_column($data, $id);
        }

        $arrayValues['keys'] = array_keys($data);

        return $arrayValues;
    }
}