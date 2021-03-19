<?php

namespace App\Controller;

use App\Repository\SQLRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

    /**
     * @Route("/database")
     */
class DatabaseController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(): Response
    {
        return $this->render('database/index.html.twig', [
            'controller_name' => 'DatabaseController',
        ]);
    }

/**
     * @Route("/drop", name="drop")
     */
    public function drop(EntityManagerInterface $entityManager, SQLRepository $sqlRepository): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $start = microtime(true);
        set_time_limit(600000);
        $sqlRepository->dropDatabase($entityManager);
        $end = microtime(true);

        $execTime = ($end - $start);

        return $this->render('database/rapport_execution.html.twig', [
            'controller_name' => 'DatabaseController',
            'execTime' => $execTime,
            'title' => "Création de données",
        ]);
    }


    /**
     * @Route("/form", name="database_form", methods={"POST"})
     */
    public function requestForm(EntityManagerInterface $entityManager, SQLRepository $sqlRepository,Request $request): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->getConnection()->getConfiguration()->setSQLLogger(null);
        $nb_user = $request->request->get('nb_user');
        $nb_product = $request->request->get('nb_product');
        

        $start = microtime(true);
        set_time_limit(600000);
        $sqlRepository->createUser($entityManager,$nb_user,$nb_product);
        set_time_limit(600000);
        $sqlRepository->createProduct($entityManager,$nb_user,$nb_product);
        set_time_limit(600000);
        $sqlRepository->createPurchase($entityManager,$nb_user,$nb_product);
        set_time_limit(600000);
        $sqlRepository->createFriend($entityManager,$nb_user,$nb_product);
        $end = microtime(true);

        $execTime = ($end - $start);

        return $this->render('database/rapport_execution.html.twig', [
            'controller_name' => 'DatabaseController',
            'execTime' => $execTime,
            'title' => "Création de données",
        ]);
    }
}
