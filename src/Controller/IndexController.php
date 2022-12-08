<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

class IndexController extends AbstractController
{
    public function index()
    {

        $number = random_int(0, 100);

        return $this->render('index/index.html.twig', [
            'number' => $number,
        ]);
    }
}
