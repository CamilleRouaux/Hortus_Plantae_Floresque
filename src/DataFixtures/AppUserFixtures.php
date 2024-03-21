<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Utility\Time\TimeConverter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class AppUserFixtures extends Fixture
{
    private $passwordHasher;
    // injecter une dépendance
    public function __construct(UserPasswordHasherInterface $passwordHasherInterface)
    {
        $this->passwordHasher = $passwordHasherInterface;
    }

    public function load(ObjectManager $manager): void
    {
        $member = new User();
        $member->setFirstname('John');
        $member->setLastname('Doe');
        $member->setCity('shoelcher');
        $member->setCountry('France');
        $member->setStatus(true);
        $member->setEmail('member@hotus.com');
        $hashedPassword = $this->passwordHasher->hashPassword($member, 'Hortus');
        $member->setPassword($hashedPassword);
        $member->setRoles(['ROLE_MEMBER']);

        $manager->persist($member);

        $redactorUser = new User();
        $redactorUser->setFirstname('Jane');
        $redactorUser->setLastname('Smith');
        $redactorUser->setCity('shoelcher');
        $redactorUser->setCountry('France');
        $redactorUser->setStatus(true);
        $redactorUser->setEmail('redactor@hotus.com');
        $hashedPassword = $this->passwordHasher->hashPassword($redactorUser, 'Hortus');
        $redactorUser->setPassword($hashedPassword);
        $redactorUser->setRoles(['ROLE_REDACTOR']);

        $manager->persist($redactorUser);

        $adminUser = new User();
        $adminUser->setFirstname('Admin');
        $adminUser->setLastname('User');
        $adminUser->setCity('shoelcher');
        $adminUser->setCountry('France');
        $adminUser->setStatus(true);
        $adminUser->setEmail('admin@hotus.com');
        $hashedPassword = $this->passwordHasher->hashPassword($adminUser, 'Hortus');
        $adminUser->setPassword($hashedPassword);
        $adminUser->setRoles(['ROLE_ADMIN']);

        $manager->persist($adminUser);

        $manager->flush();
    }
}
