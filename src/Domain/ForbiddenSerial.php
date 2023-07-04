<?php

namespace PriNikApp\FrontTest\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'forbidden_serials')]
final class ForbiddenSerial extends Serial
{
    #[Id, Column(type: 'integer', length: 10), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'string', length: 200)]
    private string $serial;

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
    public function getSerial(): string
    {
        return $this->serial;
    }

    /**
     * @param string $serial
     */
    public function setSerial(string $serial): void
    {
        $this->serial = $serial;
    }
}
