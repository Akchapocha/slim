<?php

namespace PriNikApp\FrontTest\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use PriNikApp\FrontTest\Domain\Access;
use PriNikApp\FrontTest\Domain\ServiceStatusMessage as Message;

final class AccessService
{
    private array $access;
    private array $post;
    public function __construct(private readonly EntityManager $em)
    {
    }

    /**
     * @param array $post
     * @throws Exception
     */
    public function setAccess(array $post): void
    {
        $this->post = $post;
        $this->post['idPages'] = $post['idPages'] ?? [];
        $this->access = $this->getAccessList();
        try {
            $this->deleteAccess();
            $this->addAccess();
            $this->em->flush();
        } catch (ORMException) {
            throw new Exception(Message::AccessNotUpdated->value);
        }
    }

    /**
     * @param int $uid
     * @return int[]
     */
    public function getAccessIdList(int $uid): array
    {
        $this->post['idUser'] = $uid;
        return array_map(
            fn($a): int => $a->getModule(),
            $this->getAccessList()
        );
    }

    /**
     * @return Access[]
     */
    private function getAccessList(): array
    {
        return $this->em
            ->getRepository(Access::class)
            ->findBy(['uid' => $this->post['idUser']]);
    }

    /**
     * @throws ORMException
     */
    private function deleteAccess(): void
    {
        foreach ($this->access as $access) {
            $match = 0;
            foreach ($this->post['idPages'] as $page) {
                $access->getModule() !== $page ?: $match = 1;
            }
            $match == 1 ?: $this->em->remove($access);
        }
    }

    /**
     * @throws ORMException
     */
    private function addAccess(): void
    {
        foreach ($this->post['idPages'] as $page) {
            $match = 0;
            foreach ($this->access as $access) {
                $access->getModule() !== $page ?: $match = 1;
            }
            $match == 1 ?: $this->newAccess($page);
        }
    }

    /**
     * @throws ORMException
     */
    private function newAccess(int $page): void
    {
        $newAccess = new Access();
        $newAccess->setUid($this->post['idUser']);
        $newAccess->setModule($page);
        $this->em->persist($newAccess);
    }
}
