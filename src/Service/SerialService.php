<?php

namespace PriNikApp\FrontTest\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use PriNikApp\FrontTest\Domain\ForbiddenSerial;
use PriNikApp\FrontTest\Domain\StageInvoiceSerial;
use PriNikApp\FrontTest\Domain\Serial;
use PriNikApp\FrontTest\Domain\ServiceStatusMessage as Message;

final class SerialService
{
    private EntityRepository $forbiddenRepo;
    private EntityRepository $stageInvoiceRepo;
    private EntityRepository $serialRepo;

    public function __construct(private readonly EntityManager $em)
    {
        $this->forbiddenRepo = $em->getRepository(ForbiddenSerial::class);
        $this->stageInvoiceRepo = $em->getRepository(StageInvoiceSerial::class);
        $this->serialRepo = $em->getRepository(Serial::class);
    }

    /**
     * @param string $serial
     *
     * @return void
     * @throws Exception
     */
    public function addSerial(string $serial): void
    {
        try {
            $newSerial = new ForbiddenSerial();
            $newSerial->setSerial($serial);
            $this->em->persist($newSerial);
            $this->em->flush();
        } catch (ORMException) {
            throw new Exception(Message::SerialNotSaved->value);
        }
    }

    /**
     * @param string $serial
     *
     * @return void
     * @throws Exception
     */
    public function deleteSerial(string $serial): void
    {
        try {
            $this->em->remove($this->forbiddenRepo->findOneBy(['serial' => $serial]));
            $this->em->flush();
        } catch (ORMException) {
            throw new Exception(Message::SerialNotDeleted->value);
        }
    }

    /**
     * @return array
     */
    public function getForbiddenSerialsArray(): array
    {
        $forbidden = $this->getForbiddenSerials();
        $staged = $this->getForbiddenStageInvoiceSerials($forbidden);
        return [
            'staged' => $staged,
            'forbidden' => array_udiff($forbidden, $staged, function ($f, $s) {
                return $f->getSerial() <=> $s->getSerial();
            })
        ];
    }

    /**
     * @return ForbiddenSerial[]
     */
    private function getForbiddenSerials(): array
    {
        return $this->forbiddenRepo->findBy([], limit: 1000);
    }

    /**
     * @param array $criteria
     * @return StageInvoiceSerial[]
     */
    private function getStageInvoiceSerials(array $criteria = []): array
    {
        return $this->stageInvoiceRepo->findBy($criteria, limit: 1000);
    }

    /**
     * @param ForbiddenSerial[] $forbidden
     *
     * @return StageInvoiceSerial[]
     */
    public function getForbiddenStageInvoiceSerials(array $forbidden): array
    {
        return $this->getStageInvoiceSerials(criteria: [
            'serial' => array_map(
                fn($s): string => $s->getSerial(),
                $forbidden
            )
        ]);
    }
}
