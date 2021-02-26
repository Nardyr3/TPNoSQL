<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {

    $data = [
              15339,
              21345,
              18483,
              24003,
              23489,
              24092,
              26312
    ];

    $label = [
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday'
    ];
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
            'data' => $data,
            'label' => $label
        ]);
    }

}
