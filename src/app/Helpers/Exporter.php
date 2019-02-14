<?php
/*
|--------------------------------------------------------------------------
| Exporter helper
|--------------------------------------------------------------------------
|
| Exporter Helper
|
*/

namespace App\Helpers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class Exporter
{
    protected $save_path;

    public function __construct($settings)
    {
        $this->save_path = $settings->get('tmp_path');
    }

    public function xlsx($filename, $data)
    {
        $file = $this->save_path . DIRECTORY_SEPARATOR . $filename . '.xlsx';
        $ss = new Spreadsheet();
        $ss->getActiveSheet()->fromArray($data);
        $writer = new Xlsx($ss);
        $writer->save($file);
        return $file;
    }

    public function csv($filename, $data)
    {
        $file = $this->save_path . DIRECTORY_SEPARATOR . $filename . '.csv';
        $ss = new Spreadsheet();
        $ss->getActiveSheet()->fromArray($data);
        $writer = new Csv($ss);
        $writer->setDelimiter(',');
        $writer->setEnclosure('');
        $writer->setLineEnding("\r\n");
        $writer->setSheetIndex(0);
        $writer->save($file);
        return $file;
    }
}
