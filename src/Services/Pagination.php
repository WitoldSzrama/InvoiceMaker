<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class Pagination
{
    public function getPagination(ServiceEntityRepository $repository, Request $request, PaginatorInterface $paginator, User $user)
    {
        $query = $repository->getQueryByUser($user);

        return $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            10
        );
    }
}