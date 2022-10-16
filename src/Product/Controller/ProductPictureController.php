<?php

namespace App\Product\Controller;

use App\Product\Entity\Product;
use App\Product\Entity\ProductPicture;
use App\Product\Form\ProductPictureType;
use App\Product\Message\NewProductPicture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;

#[Route('/product/picture')]
class ProductPictureController extends AbstractController
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
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

            $this->bus->dispatch(
                new NewProductPicture($pictureFileName, $product->getId(), $productPicture->getType()->getId())
            );

            return $this->json([], Response::HTTP_CREATED);
        }

        return $this->render('product/product_picture/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
}