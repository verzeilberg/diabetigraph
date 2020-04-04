<?php

namespace App\Service\Factory;

use App\Service\ProductGroupService;

class ProductGroupServiceFactory
{

    public function __invoke()
    {
        $doctrineManager  = $this->container->get('doctrine');

        return new ProductGroupService(
            $doctrineManager
        );
    }
}
