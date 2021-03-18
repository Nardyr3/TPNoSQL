<?php

namespace App\Controller;

use App\Repository\RequestRepository;
use App\Repository\SQLRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/request")
 */
class RequestController extends AbstractController
{

    
    /**
     * @Route("/", name="request")
     */
    public function index(Request $request): Response
    {
        return $this->render('request/index.html.twig', [
            'controller_name' => 'RequestController',
        ]);
    }

    /**
     * @Route("/form", name="request_form", methods={"POST"})
     */
    public function requestForm(EntityManagerInterface $entityManager, SQLRepository $sqlRepository,Request $request): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $request_id = $request->request->get('id');
        $user_id = $request->request->get('user_id');
        $level = $request->request->get('level');
        $product_id = $request->request->get('product_id');
        

        $start = microtime(true);
        set_time_limit(600);
        $products = $sqlRepository->getAllPurchaseByFollower($entityManager,$user_id,$level);
        $end = microtime(true);

        $execTime = ($end - $start);

        return $this->render('request/products_by_follower.html.twig', [
            'controller_name' => 'RequestController',
            'products' => $products,
            'execTime' => $execTime,
            'id' => $request_id,
        ]);
    }
}
