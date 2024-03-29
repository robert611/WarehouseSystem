<?php

namespace App\Product\Controller;

use App\Product\Form\DTO\ProductSearchEngineDTO;
use App\Product\Form\ProductSearchEngineType;
use App\Product\Repository\ProductRepository;
use App\Product\Serializer\SearchEngineSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product/search/engine')]
class ProductSearchEngineController extends AbstractController
{
    #[Route('/', name: 'product_search_engine_index')]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductSearchEngineType::class, new ProductSearchEngineDTO(), [
            'action' => $this->generateUrl('product_search_engine_index'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productSearchEngineFormDTO = $form->getData();
            $products = $productRepository->searchEngineResults($productSearchEngineFormDTO);

            return new JsonResponse(['products' => SearchEngineSerializer::normalizeSearchEngineResults($products)]);
        }

        return $this->render('product/search_engine/index.html.twig', [
            'form' => $form->createView(),
            'products' => $products ?? [],
        ]);
    }
}