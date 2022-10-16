<?php

namespace App\Product\Controller;

use App\Product\Entity\Product;
use App\Product\Entity\ProductPicture;
use App\Product\Form\ProductPictureType;
use App\Product\Repository\ProductPictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product/picture')]
class ProductPictureController extends AbstractController
{
    private ProductPictureRepository $productPictureRepository;

    public function __construct(ProductPictureRepository $productPictureRepository)
    {
        $this->productPictureRepository = $productPictureRepository;
    }

    #[Route('/new/{product}', name: 'app_product_picture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Product $product): Response
    {
        $productPicture = new ProductPicture();
        $form = $this->createForm(ProductPictureType::class, $productPicture, [
            'action' => $this->generateUrl('app_product_picture_new', ['product' => $product->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $request->files->get('product_picture')['path'];
            $pictureFileName = $pictureFile->getClientOriginalName();
            $pictureFile->move($this->getParameter('product_pictures_directory'), $pictureFileName);
            $productPicture->setPath($pictureFileName);
            $productPicture->setProduct($product);
            $this->productPictureRepository->add($productPicture, true);

            return $this->json([], Response::HTTP_CREATED);
        }

        return $this->render('product/product_picture/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
}