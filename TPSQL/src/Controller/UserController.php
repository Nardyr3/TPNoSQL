<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/users")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="users")
     */
    public function index(UserRepository $userRepository): Response
    {

        $users = $userRepository->getAllUsers();
        $counter = $userRepository->getCount();
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users,
            'counter' => $counter,
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

    /**
     * @Route("/form", name="showForm", methods={"POST"})
     */
    public function showForm(Request $request): Response
    {
        $email = $request->request->get('user_email');
        //print($request->request);
        
        //print($email);
        $password = $request->request->get('user_password');
        print('Print password :');
        print($password);
        return $this->render('user/trash.html.twig', [
            'controller_name' => 'UserController',
            'email' => $email,
            'password' => $password,
        ]);
    }
}
