<?php

namespace App\Service\Import;

class ImportFile{

    public static function import(ImportInterface $import_excel,$data)
    {

        return $import_excel->import($data);

    }
}
