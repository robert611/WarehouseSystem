<?php

namespace App\Tests\Application\Warehouse\Controller;

use App\DataFixtures\WarehouseStructureTreeFixtures;
use App\Security\Repository\UserRepository;
use App\Warehouse\Repository\WarehouseStructureTreeRepository;
use App\Warehouse\Validator\Leaf\CanBeUnsetFromLeafValidator;
use App\Warehouse\Validator\Leaf\CanBeWarehouseLeafValidator;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WarehouseLeafControllerTest extends WebTestCase
{
    private readonly KernelBrowser $client;
    private readonly WarehouseStructureTreeRepository $warehouseStructureTreeRepository;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->userRepository = static::getContainer()->get(UserRepository::class);
        $this->warehouseStructureTreeRepository = static::getContainer()->get(WarehouseStructureTreeRepository::class);
    }

    public function test_set_with_children_will_fail(): void
    {
        $casualUser = $this->userRepository->findOneBy(['username' => 'casual_user']);
        $this->client->loginUser($casualUser);

        $node = $this->warehouseStructureTreeRepository->findOneBy(
            ['name' => WarehouseStructureTreeFixtures::NODE_CONTAINING_CHILDREN_NAME]
        );

        $this->client->request('POST', '/warehouse/leaf/set/' . $node->getId());

        $this->assertResponseIsSuccessful();

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertTrue($responseData['error']);
        $this->assertEquals(CanBeWarehouseLeafValidator::CONTAINS_CHILDREN_MESSAGE, $responseData['errorMessage']);
        $this->assertFalse($node->isLeaf());
    }

    public function test_set_will_succeed(): void
    {
        $casualUser = $this->userRepository->findOneBy(['username' => 'casual_user']);
        $this->client->loginUser($casualUser);

        $node = $this->warehouseStructureTreeRepository->findOneBy(
            ['name' => WarehouseStructureTreeFixtures::EMPTY_NODE]
        );

        $this->client->request('POST', '/warehouse/leaf/set/' . $node->getId());

        $this->assertResponseIsSuccessful();

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertFalse($responseData['error']);
        $this->assertTrue($node->isLeaf());
    }

    public function test_unset_with_not_free_items_will_fail(): void
    {
        $casualUser = $this->userRepository->findOneBy(['username' => 'casual_user']);
        $this->client->loginUser($casualUser);

        $node = $this->warehouseStructureTreeRepository->findOneBy(
            ['name' => WarehouseStructureTreeFixtures::NODE_CONTAINING_ITEMS_NAME]
        );

        $this->assertTrue($node->isLeaf());

        $this->client->request('POST', '/warehouse/leaf/unset/' . $node->getId());

        $this->assertResponseIsSuccessful();

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertTrue($responseData['error']);
        $this->assertEquals(CanBeUnsetFromLeafValidator::CONTAINS_ITEMS_MESSAGE, $responseData['errorMessage']);
        $this->assertTrue($node->isLeaf());
    }

    public function test_unset_will_succeed(): void
    {
        $casualUser = $this->userRepository->findOneBy(['username' => 'casual_user']);
        $this->client->loginUser($casualUser);

        $node = $this->warehouseStructureTreeRepository->findOneBy(
            ['name' => WarehouseStructureTreeFixtures::NODE_CONTAINING_FREE_ITEMS_NAME]
        );

        $this->assertTrue($node->isLeaf());
        $this->assertTrue($node->getWarehouseItems()->count() > 0);

        $this->client->request('POST', '/warehouse/leaf/unset/' . $node->getId());

        $this->assertResponseIsSuccessful();

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertFalse($responseData['error']);
        $this->assertFalse($node->isLeaf());
        $this->assertEquals(0, $node->getWarehouseItems()->count());
    }
}