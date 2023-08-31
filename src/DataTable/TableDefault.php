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
            'trueValue' => '<span class="badge bg-success me-1 my-1">Oui</span>',
            'falseValue' => '<span class="badge bg-warning me-1 my-1">Non</span>',
            'globalSearchable' => $searchable,
        ];
    }

    //todo: adapt the translation format
    protected function getDateOptions($searchable = false, $field = ''): array
    {
        $options = [
            'format' => 'd/m/Y',
            'globalSearchable' => $searchable,
        ];

        if ($field) {
            $options['field'] = $field;
        }

        return $options;
    }

    protected function getActionsButtons($context, string $editPath = null, string $showPath = null, string $deletePath = null, bool $impersonate = false): string
    {
        return $this->twig->render('__includes/datatables/_datatables_actions.html.twig', [
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