<?php

namespace PriNikApp\FrontTest\Service;

use Doctrine\ORM\EntityManager;
use PriNikApp\FrontTest\Domain\User;

/**
 *
 */
final class UserService
{
    /**
     * @param EntityManager $em
     */
    public function __construct(private readonly EntityManager $em)
    {
    }

    /**
     * @return User[][]
     */
    public function getUsersTree(): array
    {
        foreach ($this->getAllSortByName() as $user) {
            $tree[mb_substr($user->getName(), 0, 1)][] = $user;
        }
        return $tree ?? [];
    }

    /**
     * @return User[]
     */
    private function getAllSortByName(): array
    {
        $query = $this->em->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.id > 0')
            ->orderBy('u.name', 'asc')
            ->getQuery();
        return $query->getResult();
    }
}
