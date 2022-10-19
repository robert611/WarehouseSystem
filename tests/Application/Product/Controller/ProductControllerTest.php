<?php

namespace App\Tests\Application\Product\Controller;

use App\Product\Repository\ProductRepository;
use App\Security\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
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
}