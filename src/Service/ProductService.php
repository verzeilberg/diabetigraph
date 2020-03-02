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
    private $productRepository;

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
}
