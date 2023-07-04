<?php

namespace PriNikApp\FrontTest\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'stage_access_list')]
final class Access
{
    #[Id, Column(type: 'integer', length: 10, unique: true), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'integer', length: 10)]
    private int $uid;

    #[Column(name: 'module_id', type: 'integer', length: 3)]
    private int $module;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUid(): int
    {
        return $this->uid;
    }

    /**
     * @return int
     */
    public function getModule(): int
    {
        return $this->module;
    }

    /**
     * @param int $uid
     */
    public function setUid(int $uid): void
    {
        $this->uid = $uid;
    }

    /**
     * @param int $module
     */
    public function setModule(int $module): void
    {
        $this->module = $module;
    }
}
