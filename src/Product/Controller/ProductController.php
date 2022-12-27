<?php

namespace App\Product\Controller;

use App\Product\Entity\Product;
use App\Product\Form\ProductType;
use App\Product\Model\Enum\SaleTypeEnum;
use App\Product\Repository\ProductRepository;
use App\Shared\HashTable;
use App\Warehouse\Service\WarehouseItem\ReleaseService;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    private readonly ReleaseService $releaseService;

    public function __construct(ReleaseService $releaseService)
    {
        $this->releaseService = $releaseService;
    }

    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        $saleCases = HashTable::getHashTableFromEnum(SaleTypeEnum::cases(), 'name');

        // Things to handle
        // Display product pictures from db not static link
        // Symfony ux package lazy images, use it should be cool
        // Symfony ux package crop image, to resize it, it would require rebuilding page for adding pictures
        // Symfony ux swap to paginate, may be used wherever. Pagination looks cool. And seems very simple
        // Symfony ux twig components, these are lovely

        return $this->render('product/product/index.html.twig', [
            'products' => $productRepository->findBy([], ['id' => 'DESC']),
            'saleTypeEnum' => $saleCases,
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCreatedAt(new DateTimeImmutable());
            $product->setUser($this->getUser());
            $productRepository->add($product, true);

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        $saleCases = HashTable::getHashTableFromEnum(SaleTypeEnum::cases(), 'value');

        return $this->render('product/product/show.html.twig', [
            'product' => $product,
            'saleCases' => $saleCases,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->add($product, true);

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->getUser() !== $product->getUser() && $this->isGranted('ROLE_ADMIN') === false) {
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $this->releaseService->releaseProductWarehouseItems($product, $this->getUser());
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
