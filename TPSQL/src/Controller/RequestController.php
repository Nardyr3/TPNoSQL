<?php

namespace App\Controller;

use App\Repository\NoSQLRepository;
use App\Repository\RequestRepository;
use App\Repository\SQLRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Laudis\Neo4j\ClientBuilder;

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
    public function requestForm(EntityManagerInterface $entityManager, SQLRepository $sqlRepository,NoSQLRepository $noSQLRepository,Request $request): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $request_id = $request->request->get('id');
        $user_id = $request->request->get('user_id');
        $level = $request->request->get('level');
        $product_id = $request->request->get('product_id');
        $database_id = $request->request->get('database_id');
        set_time_limit(6000);

        $start = microtime(true);
        
        switch ($database_id) {
            case 1:
                $database = "MySQL";
                switch ($request_id) {
                    case 1:
                        $products = $sqlRepository->getAllPurchaseByFollower($entityManager,$user_id,$level);
                        break;
                    case 2:
                        $products = $sqlRepository->getPurchaseByProduct($entityManager, $user_id, $product_id, $level);
                        break;
                    case 3:
                        break;
                }
                break;
            case 2:
                $database = "NoSQL";
                $client = ClientBuilder::create()
                ->addBoltConnection('default', sprintf('bolt://%s:%s@127.0.0.1:7687', 'neo4j', 'root'))
                ->build();
                switch ($request_id) {
                    case 1:
                        $products = $noSQLRepository->getAllPurchaseByFollower($client,$user_id,$level);
                        break;
                    case 2:
                        $products = $noSQLRepository->getPurchaseByProduct($client, $user_id, $product_id, $level);
                        break;
                    case 3:
                        $products = $noSQLRepository->getBuyersByProduct($client,$product_id,$level);
                        break;
                }
                break;
        }

        $end = microtime(true);
        $execTime = ($end - $start);

        

        return $this->render('request/products_by_follower.html.twig', [
            'controller_name' => 'RequestController',
            'products' => $products,
            'execTime' => $execTime,
            'id' => $request_id,
            'database' => $database,
        ]);
    }
}
