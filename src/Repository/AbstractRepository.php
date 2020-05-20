<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class AbstractRepository extends ServiceEntityRepository
{
    public function getQueryBuilderByUser(Users $user, string $alias = 'e')
    {
        return $this->createQueryBuilder($alias)
        ->andWhere($alias.'.user = :user')
        ->setParameter('user', $user)
        ->orderBy($alias.'.id', 'DESC');
    }

    public function getQueryByUser(Users $user, string $alias = 'e')
    {
        return $this->getQueryBuilderByUser($user, $alias)->getQuery();
    }
}
