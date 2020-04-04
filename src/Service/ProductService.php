<?php


namespace App\Service;

use App\Entity\Product\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;


class ProductService
{

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @var ProductRepository
     */
    public $productRepository;

    /**
     * ProductService constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(
        ProductRepository $productRepository
    )
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Create new product instance
     * @return Product
     */
    public function newProduct()
    {
        return new Product();
    }

    /**
     * Archive product
     * @param Product $product
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function archiveProduct(Product $product, $archive = true)
    {
        $product->setArchived($archive);
        return $this->productRepository->save($product);
    }
}
