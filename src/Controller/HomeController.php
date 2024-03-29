<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
    * @Route("/{path}", requirements={"path": ".*"}, methods={"GET"})
    */
    public function indexAction(Request $request)
    {
        return $this->render('base.html.twig');
    }
}
