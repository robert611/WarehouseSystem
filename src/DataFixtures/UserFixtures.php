<?php

namespace App\DataFixtures;

use App\Security\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const ADMIN_USER_REFERENCE = 'admin-user';
    public const CASUAL_USER_REFERENCE = 'casual-user';

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $adminUser = new User();
        $adminUser->setUsername('admin');
        $adminUser->setEmail('admin_user@gmail.com');
        $adminUser->setPassword($this->hasher->hashPassword($adminUser, 'admin'));
        $adminUser->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $casualUser = new User();
        $casualUser->setUsername('casual_user');
        $casualUser->setEmail('casual_user@gmail.com');
        $casualUser->setPassword($this->hasher->hashPassword($adminUser, 'casual_user'));
        $casualUser->setRoles(['ROLE_USER']);

        $manager->persist($adminUser);
        $manager->persist($casualUser);
        $manager->flush();

        $this->addReference(self::ADMIN_USER_REFERENCE, $adminUser);
        $this->addReference(self::CASUAL_USER_REFERENCE, $casualUser);
    }
}
