<?php


namespace App\Service;

use App\Entity\Product\Unit;
use App\Repository\UnitRepository;
use Doctrine\ORM\EntityManagerInterface;


class UnitService
{

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @var UnitRepository
     */
    public $unitRepository;

    /**
     * UnitService constructor.
     * @param UnitRepository $unitRepository
     */
    public function __construct(
        UnitRepository $unitRepository
    )
    {
        $this->unitRepository = $unitRepository;
    }

    /**
     * Create new unit instance
     * @return Unit
     */
    public function newUnit()
    {
        return new Unit();
    }

    /**
     * Archive unit
     * @param Unit $unit
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function archiveUnit(Unit $unit, $archive = true)
    {
        $unit->setArchived($archive);
        return $this->unitRepository->save($unit);
    }

    /**
     * @param Unit $unit
     * @return Unit
     */
    public function setOrderNumber(Unit $unit)
    {
        $order = 1;
        $highestUnitByOrder = $this->unitRepository->getHighestUnitByOrder();
        if (is_object($highestUnitByOrder)) {
            $order = $highestUnitByOrder->getOrder() + 1;
        }
        $unit->setOrder($order);
        return $unit;
    }
}
