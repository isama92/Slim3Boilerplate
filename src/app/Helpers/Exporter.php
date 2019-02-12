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

use SlimFacades\App;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class Exporter
{
    public static function xlsx($filename, $data)
    {
        $file = App::getContainer()->settings['tmp_path'] . DIRECTORY_SEPARATOR . $filename . '.xlsx';
        $ss = new Spreadsheet();
        $ss->getActiveSheet()->fromArray($data);
        $writer = new Xlsx($ss);
        $writer->save($file);
        return $file;
    }

    public static function csv($filename, $data)
    {
        $file = App::getContainer()->settings['tmp_path'] . DIRECTORY_SEPARATOR . $filename . '.csv';
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
