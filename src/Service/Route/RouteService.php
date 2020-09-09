<?php


namespace App\Service\Route;

use App\Entity\Route;
use App\Repository\RouteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class RouteService
{

    /** @var RouteRepository */
    public $repository;

    /** @var UrlGeneratorInterface */
    private $router;

    /**
     * RouteService constructor.
     * @param RouteRepository $repository
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        RouteRepository $repository,
        UrlGeneratorInterface $router
    )
    {
        $this->repository = $repository;
        $this->router = $router;
    }

    /**
     * Create new route instance
     * @return Route
     */
    public function newRoute()
    {
        return new Route();
    }


    public function getAllRoutes()
    {
        $collection = $this->router->getRouteCollection();
        $allRoutes = $collection->all();

        $routes = array();

        /** @var $params \Symfony\Component\Routing\Route */
        foreach ($allRoutes as $route => $params)
        {
            if (substr($route, 0, 3) === 'app') {
                $routes[] = $route;
            }
        }

        return $routes;
    }
}
