# CodeIgniterExporter

From database to csv

```php
<?php

declare(strict_types=1);

use App\Controllers\BaseController;
use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Kj8\CodeIgniterExporter\FileSystem\DirectoryEnsurer;
use Kj8\CodeIgniterExporter\Reader\IdRangeCodeIgniterDatabaseReader;
use Kj8\CodeIgniterExporter\Writer\Factory\WriterEntityFactory;
use Kj8\CodeIgniterExporter\Writer\OpenSpoutFileWriter;
use Kj8\CodeIgniterExporter\Writer\Options\CSVWriterOptions;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Writer\Exception\WriterNotOpenedException;

include __DIR__ . '/vendor/autoload.php';

class Demo extends BaseController
{
    /**
     * @throws IOException
     * @throws WriterNotOpenedException
     */
    public function csv(): ResponseInterface
    {
        $reader = new IdRangeCodeIgniterDatabaseReader(
            Database::connect(),
            'users',
            ['id', 'email', 'created_at']
        );

        $filePath = WRITEPATH . 'exports/users.csv';

        (new DirectoryEnsurer())->ensure($filePath);

        $options = new CSVWriterOptions();

        $writer = WriterEntityFactory::createCSVWriter($options);
        $fileWriter = new OpenSpoutFileWriter($writer, $filePath);

        $fileWriter->write($reader->read());

        return $this->response->setBody('CSV generated');
    }

    public function advancedReader(): ResponseInterface
    {
        $columns = [
            'table_.id AS id',
            'table_.created_by',
            'table_.created_by',
            'table_.brand_id',
            'table_.material_marketingowy',
            'table_.wartosc_bonu',
            'table_.rodzaj_nagrody',
            'SUM(table_.ilosc_bonow) AS ilosc',
            'users.name AS user_name',
            'users.surname AS user_surname',
            'users.email AS user_email',
            'users.firm AS user_firm',
            'brands.name',
        ];

        $reader = new IdRangeCodeIgniterDatabaseReader(Database::connect(), 'orders_items', $columns, function (BaseBuilder $builder): void {
            $builder
                ->join('users', 'users.id = table_.created_by')
                ->join('brands', 'brands.id = table_.brand_id')
                ->where('table_.completion_status', 'Oczekuje')
                ->groupBy('table_.created_by, table_.brand_id, table_.material_marketingowy, table_.wartosc_bonu, table_.rodzaj_nagrody');
        });

        $filePath = WRITEPATH . 'exports/users.json';

        (new DirectoryEnsurer())->ensure($filePath);

        $options = new CSVWriterOptions();

        $writer = WriterEntityFactory::createCSVWriter($options);
        $fileWriter = new OpenSpoutFileWriter($writer, $filePath);

        $fileWriter->write($reader->read());

        return $this->response->setBody('JSON generated');
    }

    public function advancedReaderAndWriter(string $filename, string $orderLabel): string
    {
        $columns = [
            'GROUP_CONCAT(table_.id) AS wszystkie_id',
            '"specjalne" AS symbol',
            'CONCAT("' . $orderLabel . '", "_", ROW_NUMBER() OVER (ORDER BY table_.id ASC)) AS nr_zamowienia',
            '"" AS data_zamowienia',
            'table_.id AS id',
            'table_.created_by',
            'table_.brand_id',
            'CONCAT(table_.material_marketingowy, " ", brands.name, " " , table_.wartosc_bonu) AS nazwa',
            'table_.wartosc_bonu',
            'table_.rodzaj_nagrody',
            'SUM(table_.ilosc_bonow) AS ilosc',
            'users.name AS user_name',
            'users.surname AS user_surname',
            'users.email AS user_email',
            'users.firm AS user_firm',
            'brands.name AS brand_name',
        ];

        $reader = new IdRangeCodeIgniterDatabaseReader($this->db, 'orders_items', $columns, function (BaseBuilder $builder) {
            $builder
                ->join('users', 'users.id = table_.created_by')
                ->join('brands', 'brands.id = table_.brand_id')
                ->where('table_.completion_status', 'Oczekuje')
                ->groupBy('table_.created_by, table_.brand_id, table_.material_marketingowy, table_.wartosc_bonu, table_.rodzaj_nagrody');
        });

        $filePath = WRITEPATH . "exports/$filename.csv";

        (new DirectoryEnsurer())->ensure($filePath);

        $options = new CSVWriterOptions();

        $writer = WriterEntityFactory::createCSVWriter($options);
        $fileWriter = new OpenSpoutFileWriter(
            $writer,
            $filePath,
            ['Symbol', 'Widok', 'Nr zamówienia', 'Nazwa', 'Ilość', 'Rodzaj nagrody', 'Data zamówienia', 'wszystkie_id'],
            ['symbol', 'user_firm', 'nr_zamowienia', 'nazwa', 'ilosc', 'rodzaj_nagrody', 'data_zamowienia', 'wszystkie_id']
        );

        try {
            $fileWriter->write($reader->read());
            return $filePath;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
```
