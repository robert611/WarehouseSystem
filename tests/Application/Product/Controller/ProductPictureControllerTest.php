<?php

namespace App\Tests\Application\Product\Controller;

use App\Product\Repository\ProductPictureRepository;
use App\Product\Repository\ProductPictureTypeRepository;
use App\Product\Repository\ProductRepository;
use App\Security\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductPictureControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;
    private ProductRepository $productRepository;
    private ProductPictureRepository $productPictureRepository;
    private ProductPictureTypeRepository $productPictureTypeRepository;
    private DataCollectorTranslator $translator;
    private string $testImage;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->userRepository = static::getContainer()->get(UserRepository::class);
        $this->productRepository = static::getContainer()->get(ProductRepository::class);
        $this->productPictureRepository = static::getContainer()->get(ProductPictureRepository::class);
        $this->productPictureTypeRepository = static::getContainer()->get(ProductPictureTypeRepository::class);
        $this->translator = static::getContainer()->get(TranslatorInterface::class);
        $this->testImage = static::getContainer()->get('parameter_bag')->get('test_image');
    }

    public function testNew(): void
    {
        $adminUser = $this->userRepository->findOneBy(['username' => 'admin']);
        $product = $this->productRepository->findOneBy([]);
        $this->client->loginUser($adminUser);

        $this->client->request('GET', '/product/picture/new/' . $product->getId());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h4', $product->getName());
        $this->assertSelectorTextContains('label', $this->translator->trans('path'));
        $this->assertSelectorTextContains('html', $this->translator->trans('type'));
    }

    public function testCreateProductPicture(): void
    {
        $adminUser = $this->userRepository->findOneBy(['username' => 'admin']);
        $product = $this->productRepository->findOneBy([]);
        $this->client->loginUser($adminUser);

        $crawler = $this->client->request('GET', '/product/picture/new/' . $product->getId());

        $formButton = $crawler->selectButton($this->translator->trans('save'));
        $form = $formButton->form();

        $form->setValues(['product_picture' => [
            'type' => $this->productPictureTypeRepository->findOneBy([])->getId()]
        ]);
        $form['product_picture[path]'] = $this->testImage;

        $productPicturesCount = $product->getProductPictures()->count();

        $this->client->submit($form);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertSame($this->productPictureRepository->count([]), $productPicturesCount + 1);
    }
}