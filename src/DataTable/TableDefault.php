<?php

namespace App\DataTable;

class TableDefault
{
    protected function getYesNoOptions($searchable = false): array
    {
        return [
            'trueValue' => 'yes',
            'falseValue' => 'no',
            'globalSearchable' => $searchable,
        ];
    }

    //todo: adapt the translation format
    protected function getDateOptions($searchable = false): array
    {
        return [
            'format' => 'd/m/Y',
            'globalSearchable' => $searchable,
        ];
    }
}