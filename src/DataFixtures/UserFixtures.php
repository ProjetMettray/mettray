<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Association;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\AssociationFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public const FAKE_USER = [
        ['a@gmail.com', ['ROLE_USER'], 'Alan', 'Daunay', '02.47.45.12.12', 'Lolita'],
        ['b@gmail.com', ['ROLE_USER'], 'Adrien', 'Cauvin', '02.47.45.12.13', 'HockeyBoy'],
        ['c@gmail.com', ['ROLE_USER'], 'Dimitri', 'Guillon', '02.47.45.12.14', 'Puchito'],
        ['d@gmail.com', ['ROLE_USER'], 'Ludovic', 'Brault', '02.47.45.12.15', 'lagoon'],
        ['e@gmail.com', ['ROLE_USER'], 'e', 'e', '02.47.45.12.56', 'lagoon']
    ];

    public const FAKE_ADMIN = [
        ['f@gmail.com', ['ROLE_ADMIN'], 'Kevin', 'Bertaux', '02.47.45.12.16', 'Mettray'],
        ['g@gmail.com', ['ROLE_ADMIN'], 'g', 'g', '02.47.45.12.17', 'Mettray']
    ];

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager)
    {

        foreach (self::FAKE_USER as $fakeuser) {
            $user = new User();

            $user
                ->setEmail($fakeuser[0])
                ->setRoles($fakeuser[1])
                ->setPassword(
                    $this->userPasswordHasher->hashPassword($user, 'a')
                )
                ->setFirstname($fakeuser[2])
                ->setLastname($fakeuser[3])
                ->setPhone($fakeuser[4])
                ->addAssociation($manager->getRepository(Association::class)
                ->findOneByName($fakeadmin[5]))
                    ;

            $manager->persist($user);
            $manager->flush();
        }

        foreach (self::FAKE_ADMIN as $fakeadmin) {

            $user = new User;

            $user
                ->setEmail($fakeadmin[0])
                ->setRoles($fakeadmin[1])
                ->setPassword(
                    $this->userPasswordHasher->hashPassword($user, 'a')
                )
                ->setFirstname($fakeadmin[2])
                ->setLastname($fakeadmin[3])
                ->setPhone($fakeadmin[4])
                ->addAssociation($manager->getRepository(Association::class)
                    ->findOneByName($fakeadmin[5]))
                    ;

            $manager->persist($user);
            $manager->flush();
        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            AssociationFixtures::class
        ];
    }
}
