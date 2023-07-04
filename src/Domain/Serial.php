<?php

namespace PriNikApp\FrontTest\Domain;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\MappedSuperclass;

#[MappedSuperclass]
abstract class Serial
{
    abstract public function getId(): int;
    abstract public function getSerial(): string;
}
