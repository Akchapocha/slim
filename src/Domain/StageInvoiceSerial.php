<?php

namespace PriNikApp\FrontTest\Domain;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'stage_invoice_serials')]
final class StageInvoiceSerial extends Serial
{
    #[Id, Column(name: 'sis_id', type: 'integer', length: 20), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(name: 'sis_invoice_id', type: 'integer', length: 10)]
    private int $invoiceId;

    #[Column(name: 'sis_pid', type: 'integer', length: 8)]
    private int $productId;

    #[Column(name: 'sis_serial', type: 'string', length: 100)]
    private string $serial;

    #[Column(name: 'sis_uid', type: 'integer', length: 10)]
    private int $userId;

    #[Column(name: 'sis_timestamp', type: 'datetime', length: 200)]
    private DateTime $dateTime;

    #[Column(name: 'sis_box', type: 'integer', length: 11)]
    private int $box;

    public function __construct()
    {
        $this->dateTime = new DateTime('now');
    }

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
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @return string
     */
    public function getSerial(): string
    {
        return $this->serial;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

}
