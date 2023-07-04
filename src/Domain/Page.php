<?php

namespace PriNikApp\FrontTest\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'stage_access')]
final class Page
{
    #[Id, Column(type: 'integer', length: 3, nullable: false), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(name: 'access_name', type: 'string', length: 50, nullable: false)]
    private string $name;

    #[Column(type: 'string', length: 50, nullable: false)]
    private $module;

    #[Column(name: 'parent_id', type: 'integer', length: 3, nullable: false)]
    private int $parent;

    #[Column(name: 'menu_level', type: 'integer', length: 3, nullable: false)]
    private int $level;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getModule(): string
    {
        return $this->module;
    }

    /**
     * @return int
     */
    public function getParent(): int
    {
        return $this->parent;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }
}
