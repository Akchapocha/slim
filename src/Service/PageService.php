<?php

namespace PriNikApp\FrontTest\Service;

use Doctrine\ORM\EntityManager;
use PriNikApp\FrontTest\Domain\Access;
use PriNikApp\FrontTest\Domain\Page;

final class PageService
{
    /**
     * @param EntityManager $em
     */
    public function __construct(private readonly EntityManager $em)
    {
    }

    public function checkAccess(string $uri): bool
    {
        foreach ($this->getMenuTree() as $pages) {
            foreach ($pages as $page) {
                if ($page->getModule() == $uri) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @return Page[][]
     */
    public function getMenuTree(): array
    {
        $tree = $this->getTree($this->getMenu());
        foreach ($tree as $item => $menu) {
            if (!isset($menu[1])) {
                unset($tree[$item]);
            }
        }
        return $tree;
    }

    /**
     * @return Page[][]
     */
    public function getPagesTree(): array
    {
        return $this->getTree($this->getAllSortBy());
    }

    public function getTitle($uri): string
    {
        return $this->em
            ->getRepository(Page::class)
            ->findOneBy(['module' => $uri])
            ->getName();
    }

    /**
     * @return Page[]
     */
    private function getMenu(): array
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb
            ->select('p')
            ->from(Page::class, 'p')
            ->join(Access::class, 'a')
            ->where('p.parent = 0')
            ->orWhere('a.uid = :uid AND p.name != \'none\'')
            ->andWhere('p.id = a.module')
            ->groupBy('p.id')
            ->orderBy('p.parent, p.level', 'ASC')
            ->setParameter('uid', $_SESSION['uid'])
            ->getQuery();
        return $query->getResult();
    }

    /**
     * @return Page[]
     */
    private function getAllSortBy(): array
    {
        $query = $this->em->createQueryBuilder()
            ->select('p')
            ->from(Page::class, 'p')
            ->where('p.name != \'none\'')
            ->orderBy('p.parent, p.level', 'asc')
            ->getQuery();
        return $query->getResult();
    }

    private function getTree(array $pages): array
    {
        foreach ($pages as $page) {
            $parent = $page->getParent();
            if ($parent == 0) {
                $tree[$page->getId()][] = $page;
            } else {
                $tree[$parent][] = $page;
            }
        }
        return $tree ?? [];
    }
}
