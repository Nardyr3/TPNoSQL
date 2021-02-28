<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DatabaseController extends AbstractController
{
    /**
     * @Route("/database", name="database")
     */
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->getAllUsers();
        return $this->render('database/index.html.twig', [
            'controller_name' => 'DatabaseController',
            'users' => $users,
        ]);
    }
}
