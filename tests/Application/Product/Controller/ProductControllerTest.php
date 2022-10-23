<?php

namespace App\Tests\Application\Product\Controller;

use App\Product\Model\Dictionary\SaleTypeDictionary;
use App\Product\Model\Enum\SaleTypeEnum;
use App\Product\Repository\ProductRepository;
use App\Security\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;
    private ProductRepository $productRepository;
    private DataCollectorTranslator $translator;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->userRepository = static::getContainer()->get(UserRepository::class);
        $this->productRepository = static::getContainer()->get(ProductRepository::class);
        $this->translator = static::getContainer()->get(TranslatorInterface::class);
    }

    public function testIndex(): void
    {
        $casualUser = $this->userRepository->findOneBy(['username' => 'casual_user']);
        $firstProduct = $this->productRepository->findOneBy([], ['id' => 'DESC']);
        $this->client->loginUser($casualUser);

        $this->client->request('GET', '/product/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('#add-product-link > button', $this->translator->trans('add_product'));
        $this->assertSelectorTextContains('html', $firstProduct->getName());
        $this->assertSelectorTextContains('html', $firstProduct->getDescription());
        $this->assertSelectorTextContains('html', ucfirst($this->translator->trans('add_picture')));
        $this->assertSelectorTextContains('html', ucfirst($this->translator->trans('edit')));
    }

    public function testNew(): void
    {
        $casualUser = $this->userRepository->findOneBy(['username' => 'casual_user']);
        $this->client->loginUser($casualUser);

        $crawler = $this->client->request('GET', '/product/new');

        $productForm = $crawler->filter('#product-form')->getNode(0);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('html', $this->translator->trans('save'));
        $this->assertSelectorTextContains('html', $this->translator->trans('back_to_list'));
        $this->assertSame('product', $productForm->getAttribute('name'));
    }

    public function testCreateProduct(): void
    {
        $casualUser = $this->userRepository->findOneBy(['username' => 'casual_user']);
        $this->client->loginUser($casualUser);

        $crawler = $this->client->request('GET', '/product/new');

        $formButton = $crawler->selectButton($this->translator->trans('save'));
        $form = $formButton->form();

        $productData = $this->getProductData();

        $form['product[name]'] = $productData['name'];
        $form['product[description]'] = $productData['description'];
        $form['product[auctionPrice]'] = $productData['auctionPrice'];
        $form['product[buyNowPrice]'] = $productData['buyNowPrice'];
        $form['product[saleType]'] = $productData['saleType'];

        $productsCount = $this->productRepository->count([]);

        $this->client->submit($form);

        $lastProduct = $this->productRepository->findOneBy([], ['id' => 'DESC']);

        $this->assertResponseStatusCodeSame(Response::HTTP_SEE_OTHER);
        $this->assertSame($this->productRepository->count([]), $productsCount + 1);
        $this->assertSame($lastProduct->getName(), $productData['name']);
        $this->assertSame($lastProduct->getDescription(), $productData['description']);
        $this->assertSame($lastProduct->getAuctionPrice(), $productData['auctionPrice']);
        $this->assertSame($lastProduct->getBuyNowPrice(), $productData['buyNowPrice']);
        $this->assertSame($lastProduct->getSaleType(), (int) $productData['saleType']);
    }

    public function testShow(): void
    {
        $casualUser = $this->userRepository->findOneBy(['username' => 'casual_user']);
        $this->client->loginUser($casualUser);

        $firstProduct = $this->productRepository->findOneBy([], ['id' => 'DESC']);

        $this->client->request('GET', '/product/' . $firstProduct->getId());

        $saleTypeTranslation = $this->translator->trans(
            SaleTypeDictionary::translateToString($firstProduct->getSaleType())
        );

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $firstProduct->getName());
        $this->assertSelectorTextContains('#product-description-section > p', $firstProduct->getDescription());
        $this->assertSelectorTextContains('#product-other-info-section', $firstProduct->getCreatedAt()->format('Y-m-d H:i:s'));
        $this->assertSelectorTextContains('#product-other-info-section', $firstProduct->getBuyNowPrice());
        $this->assertSelectorTextContains('#product-other-info-section', $firstProduct->getAuctionPrice());
        $this->assertSelectorTextContains('#product-other-info-section', $saleTypeTranslation);
    }

    public function testDelete(): void
    {
        $adminUser = $this->userRepository->findOneBy(['username' => 'admin']);
        $this->client->loginUser($adminUser);

        $crawler = $this->client->request('GET', '/product/');

        $productsCount = $this->productRepository->count([]);

        $formButton = $crawler->selectButton($this->translator->trans('delete'));
        $this->client->submit($formButton->form());

        $this->assertResponseStatusCodeSame(Response::HTTP_SEE_OTHER);
        $this->assertSame($this->productRepository->count([]), $productsCount - 1);
    }

    public function testForUnAuthorizedDeletion(): void
    {
        $userWithoutProducts = $this->userRepository->findOneBy(['username' => 'user_without_products']);
        $this->client->loginUser($userWithoutProducts);

        $product = $this->productRepository->findOneBy([]);
        $productsCount = $this->productRepository->count([]);

        $this->client->request('POST', '/product/' . $product->getId());

        $this->assertSame($this->productRepository->count([]), $productsCount);
    }

    private function getProductData(): array
    {
        return [
            'name' => 'Test Name',
            'description' => 'Test Description',
            'auctionPrice' => 100.0,
            'buyNowPrice' => 200.0,
            'saleType' => (string) SaleTypeEnum::BOTH->value,
        ];
    }
}