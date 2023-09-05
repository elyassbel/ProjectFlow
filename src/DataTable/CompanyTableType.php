<?php

namespace App\DataTable;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Symfony\Component\Intl\Countries;

class CompanyTableType extends TableDefault implements DataTableTypeInterface
{

    public function configure(DataTable $dataTable, array $options)
    {
        /** @var User $user */
        $user = $options['user'];

        $dataTable
            ->add('name', TextColumn::class, [
                'field' => 'company.name',
            ])
            ->add('city', TextColumn::class, [
                'field' => 'address.city',
            ])
            ->add('country', TextColumn::class, [
                'field' => 'address.country',
                'render' => function($value, $context) {
                    return Countries::getName($value);
                },
            ])
            ->add('mainContact', TextColumn::class, [
                'field' => 'company.mainContact',
                'searchable' => false,
                'orderable' => false,
            ])
            ->add('actions', TextColumn::class, [
                'render' => function($value, $context) {
                    return $this->getActionsButtons($context);
                },
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Company::class,
                'query' => function (QueryBuilder $builder) use ($user) {
                    $builder
                        ->select('company')
                        ->addSelect('address')
                        ->from(Company::class, 'company')
                        ->leftJoin('company.address', 'address')
                        ->andWhere('company.userProfile = :user_profile')->setParameter('user_profile', $user->getUserProfile())
                    ;
                },
            ]);
    }

}