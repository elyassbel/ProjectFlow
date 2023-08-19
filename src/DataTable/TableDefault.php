<?php

namespace App\DataTable;

use Twig\Environment;

class TableDefault
{
    public function __construct(private readonly Environment $twig)
    {
    }

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

    protected function getActionsButtons($context, string $editPath = null, string $showPath = null, string $deletePath = null, bool $impersonate = false): string
    {
        return $this->twig->render('_common/_datatables_actions.html.twig', [
            'row' => $context,
            'path' => [
                'edit' => $editPath,
                'show' => $showPath,
                'delete' => $deletePath,
            ],
            'impersonate' => $impersonate,
        ]);
    }
}