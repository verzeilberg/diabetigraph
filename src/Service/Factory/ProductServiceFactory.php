<?php

namespace App\Service\Factory;

use App\Service\ProductService;

class ProductServiceFactory
{

    public function __invoke()
    {
        $doctrineManager  = $this->container->get('doctrine');

        return new ProductService(
            $doctrineManager
        );
    }
}
