<?php

namespace App\DataTable;

use App\Entity\User;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\BoolColumn;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class UserTableType extends TableDefault implements DataTableTypeInterface
{
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('admin', TextColumn::class, [
                'render' => function($value, $context) {
                    return $context->hasRole('ROLE_ADMIN') ? '<span class="badge bg-danger" title="Admin">A</span>' : '';
                }
            ])
            ->add('firstName', TextColumn::class, [
                'field' => 'userProfile.firstName'
            ])
            ->add('lastName', TextColumn::class, [
                'field' => 'userProfile.lastName'
            ])
            ->add('email', TextColumn::class)
            ->add('verified', BoolColumn::class, $this->getYesNoOptions())
            ->add('enabled', BoolColumn::class, $this->getYesNoOptions())
            ->add('createdAt', DateTimeColumn::class, $this->getDateOptions(false,'userProfile.createdAt'))
            ->add('actions', TextColumn::class, [
                'render' => function($value, $context) {
                    return $this->getActionsButtons($context, 'app_admin_user_edit', 'app_admin_user_show', 'app_admin_user_delete', true);
                },
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => User::class,
            ]);
    }
}