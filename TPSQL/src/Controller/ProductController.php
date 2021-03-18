<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/products", name="product")
 */
class ProductController extends AbstractController
{

/**
     * @Route("/")
     */
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->getAllProducts();
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products,
        ]);
    }

    /**
     * @Route("/{id}", name="show_product")
     */
    public function showProducts(ProductRepository $productRepository, String $id): Response
    {
        $products = $productRepository->findByUser($id);
        return $this->render('product/product_by_user.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products,
        ]);
    }
}
