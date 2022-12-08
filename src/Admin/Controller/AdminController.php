<?php
// src/Admin/Controller/AdminController.php
namespace App\Admin\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

class AdminController extends AbstractController
{
    public function index()
    {

        return $this->render('admin/index.html.twig', [
        ]);

    }
}
