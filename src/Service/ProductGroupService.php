<?php


namespace App\Service;

use App\Entity\Product\ProductGroup;
use App\Repository\ProductGroupRepository;
use Doctrine\ORM\EntityManagerInterface;


class ProductGroupService
{

    /**
     * @var ProductGroupRepository
     */
    public $productGroupRepository;

    /**
     * ProductService constructor.
     * @param ProductGroupRepository $productGroupRepository
     */
    public function __construct(
        ProductGroupRepository $productGroupRepository
    )
    {
        $this->productGroupRepository = $productGroupRepository;
    }

    /**
     * Create new product group instance
     * @return ProductGroup
     */
    public function newProductGroup()
    {
        return new ProductGroup();
    }

    /**
     * Archive product group
     * @param ProductGroup $productGroup
     * @param bool $archive
     * @return mixed
     */
    public function archiveProductGroup(ProductGroup $productGroup, $archive = true)
    {
        $productGroup->setArchived($archive);
        return $this->productGroupRepository->save($productGroup);
    }
}
