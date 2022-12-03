<?php

namespace App\Product\Controller;

use App\Product\Form\DTO\ProductSearchEngineDTO;
use App\Product\Form\ProductSearchEngineType;
use App\Product\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product/search/engine')]
class ProductSearchEngineController extends AbstractController
{
    #[Route('/', name: 'product_search_engine_index')]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductSearchEngineType::class, new ProductSearchEngineDTO());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render('product/search_engine/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}