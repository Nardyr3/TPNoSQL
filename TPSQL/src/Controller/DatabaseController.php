<?php

namespace App\Controller;

use App\Repository\NoSQLRepository;
use App\Repository\SQLRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Laudis\Neo4j\ClientBuilder;

    /**
     * @Route("/database")
     */
class DatabaseController extends AbstractController
{
    /**
     * @Route("/", name="database")
     */
    public function index(EntityManagerInterface $entityManager,SQLRepository $sqlRepository, NoSQLRepository $noSQLRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $client = ClientBuilder::create()
            ->addBoltConnection('default', sprintf('bolt://%s:%s@127.0.0.1:7687', 'neo4j', 'root'))
            ->build();

        $counterSQL = $sqlRepository->getCount($entityManager);
        $counterNoSQL = $noSQLRepository->getCount($client);

        return $this->render('database/index.html.twig', [
            'controller_name' => 'DatabaseController',
            'counterSQL' => $counterSQL,
            'counterNoSQL' => $counterNoSQL,
        ]);
    }

/**
     * @Route("/drop", name="drop_form", methods={"POST"})
     */
    public function drop(EntityManagerInterface $entityManager, SQLRepository $sqlRepository, NoSQLRepository $noSQLRepository, Request $request): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $database_id = $request->request->get('id');
        set_time_limit(600000);
        if ($database_id == 1){
            $database = "MySQL";
            $start = microtime(true);
            $sqlRepository->dropDatabase($entityManager);
            $end = microtime(true);
            $execTime = ($end - $start);
            $counter = $sqlRepository->getCount($entityManager);
        }
        else if ($database_id ==2){
            $client = ClientBuilder::create()
            ->addBoltConnection('default', sprintf('bolt://%s:%s@127.0.0.1:7687', 'neo4j', 'root'))
            ->build();

            $database = "NoSQL";
            $start = microtime(true);
            $noSQLRepository->dropDatabase($client);
            $end = microtime(true);
            $execTime = ($end - $start);
            $counter = $noSQLRepository->getCount($client);
        }

        return $this->render('database/rapport_execution.html.twig', [
            'controller_name' => 'DatabaseController',
            'execTime' => $execTime,
            'title' => "Création de données",
            'counter' => $counter,
            'database' => $database,
        ]);
    }


    /**
     * @Route("/form", name="database_form", methods={"POST"})
     */
    public function requestForm(EntityManagerInterface $entityManager, SQLRepository $sqlRepository, NoSQLRepository $noSQLRepository, Request $request): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->getConnection()->getConfiguration()->setSQLLogger(null);
        $nb_user = $request->request->get('nb_user');
        $nb_product = $request->request->get('nb_product');
        $database_id = $request->request->get('id');
        set_time_limit(600000);
        if ($database_id == 1){
            $database = "MySQL";
            $start = microtime(true);

            $sqlRepository->createUser($entityManager,$nb_user,$nb_product);
            $sqlRepository->createProduct($entityManager,$nb_user,$nb_product);
            $sqlRepository->createPurchase($entityManager,$nb_user,$nb_product);
            $sqlRepository->createFriend($entityManager,$nb_user,$nb_product);
            $end = microtime(true);

            $execTime = ($end - $start);
            $counter = $sqlRepository->getCount($entityManager);
        }
        else if ($database_id ==2){
            $database = "NoSQL";
            $start = microtime(true);

            $client = ClientBuilder::create()
            ->addBoltConnection('default', sprintf('bolt://%s:%s@127.0.0.1:7687', 'neo4j', 'root'))
            ->build();

            $noSQLRepository->createUser($client, $nb_user);
            $noSQLRepository->createProduct($client, $nb_product);
            $noSQLRepository->createPurchase($client, $nb_user,$nb_product);
            $noSQLRepository->createFriend($client, $nb_user);
            $end = microtime(true);
            $execTime = ($end - $start);
            $counter = $noSQLRepository->getCount($client);
        }
           

        

        return $this->render('database/rapport_execution.html.twig', [
            'controller_name' => 'DatabaseController',
            'execTime' => $execTime,
            'title' => "Création de données",
            'counter' => $counter,
            'database' => $database,
        ]);
    }
}
