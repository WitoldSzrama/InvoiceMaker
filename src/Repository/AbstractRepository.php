<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class AbstractRepository extends ServiceEntityRepository
{
    public function getQueryBuilderByUser(User $user, string $alias = 'e')
    {
        return $this->createQueryBuilder($alias)
        ->andWhere($alias.'.user = :user')
        ->setParameter('user', $user)
        ->orderBy($alias.'.id', 'DESC');
    }

    public function getQueryByUser(User $user, string $alias = 'e')
    {
        return $this->getQueryBuilderByUser($user, $alias)->getQuery();
    }
}