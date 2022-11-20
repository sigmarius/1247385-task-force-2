<?php
namespace Taskforce\Service;

use Taskforce\Exceptions;

class SqlFromCsvCreater
{
    private string $csvFile;
    private \SplFileObject $fileObject;

    private array $result = [];

    private array $columns = [];

    private string $sqlFileName = '';

    public function __construct(string $csvFile, array $columns = [])
    {
        $this->csvFile = $_SERVER['DOCUMENT_ROOT'] . $csvFile;
        $this->columns = $columns;
    }

    public function importCsv(): array
    {
        if (!file_exists($this->csvFile)) {
            throw new Exceptions\SourceFileException("Файл не существует");
        }

        if (pathinfo($this->csvFile, PATHINFO_EXTENSION) !== 'csv') {
            throw new Exceptions\FileFormatException("Допустимый формат для загрузки - csv");
        }

        try {
            $this->fileObject = new \SplFileObject($this->csvFile);
        } catch (\RuntimeException $exception) {
            throw new Exceptions\SourceFileException("Не удалось открыть файл на чтение");
        }

        $this->fileObject->setFlags(
            \SplFileObject::READ_CSV
            | \SplFileObject::READ_AHEAD
            | \SplFileObject::SKIP_EMPTY
            | \SplFileObject::DROP_NEW_LINE
        );

        foreach ($this->getNextLine() as $line) {
            $this->result[] = $line;
        }

        return $this->result;
    }

    private function getNextLine(): ?iterable {
        // пропустим первую строку, заголовки передаем в $columns
        $this->fileObject->fgetcsv();

        while (!$this->fileObject->eof()) {
            yield $this->fileObject->fgetcsv();
        }
    }

    private function getFileName(): string
    {
        return $this->fileObject->getBasename('.'.$this->fileObject->getExtension());
    }

    public function createSqlFile(): void
    {
        $data = $this->importCsv();
        $sqlFileName = $this->getFileName();
        $file = new \SplFileObject($sqlFileName . '.sql', 'w');

        $sqlStrings = [];
        foreach ($data as $dataString) {
            $values = '"' . implode('","', $dataString) . '"';

            $string = 'INSERT INTO ' . $sqlFileName . ' (' . implode(",", $this->columns) .') '
                        . 'VALUES (' . $values . ')';

            $text = trim($string, '\'');
            var_dump($text);


            $sqlStrings[] = [$text];
        }

        foreach ($sqlStrings as $string) {
            $file->fputcsv($string, ',', '\'');
        }
    }

}
