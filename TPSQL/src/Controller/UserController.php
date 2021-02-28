<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/users", name="users")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="users")
     */
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->getAllUsers();
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users,
        ]);
    }

    /**
     * @Route("/{id}/products", name="show_product")
     */
    public function showProducts(UserRepository $userRepository, String $id): Response
    {
        $products = $userRepository->findByPurchase($id);
        return $this->render('user/list_product.html.twig', [
            'controller_name' => 'UserController',
            'products' => $products,
        ]);
    }
}
