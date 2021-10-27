<?php

namespace App\DataFixtures;

use App\Entity\Room;
use App\Entity\User;
use App\Entity\Association;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const FAKE_USER = [
        ['a@gmail.com',['ROLE_USER'],'a','a'],
        ['b@gmail.com',['ROLE_USER'],'b','b'],
        ['c@gmail.com',['ROLE_USER'],'c','c'],
        ['d@gmail.com',['ROLE_USER'],'d','d'],
        ['e@gmail.com',['ROLE_USER'],'e','e']
    ];

    public const FAKE_ADMIN = [
        ['f@gmail.com',['ROLE_ADMIN'],'f','f'],
        ['g@gmail.com',['ROLE_ADMIN'],'g','g']
    ];
    
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager)
    {

        foreach(self::FAKE_ADMIN as $fakeadmin) {
            $user = new User();

            $user
            ->setEmail($fakeadmin[0])
            ->setRoles($fakeadmin[1])
            ->setPassword(
                $this->userPasswordHasher->hashPassword($user, 'a')
            )
            ->setFirstname($fakeadmin[2])
            ->setLastname($fakeadmin[3])
            ->setPhone($fakeadmin[4])
            ->addAssociaton($manager->getRepository(Association::class)
            ->findOneBy(['name' => $fakeadmin[5]]))
            ->addRoom($manager->getRepository(Room::class)
            ->findOneBy(['name' => $fakeadmin[6]]));

                $manager->persist($user);
                $manager->flush();
        }
    
        $manager->persist($this->createAdminUser());
        $manager->flush();

        $manager->flush();
    }
    private function createAdminUser(): User
    {
        $admin = new User;

        $admin
        ->setEmail('z.z@z.fr')
        ->setRoles(['ROLE_SUPER_ADMIN'])
        ->setPassword(
            $this->userPasswordHasher->hashPassword($admin, 'z')
        )
        ->setFirstname('Z')
        ->setLastname('Z')
        ->setPhone('23.38.85.18.15')
        ->addAssociaton($manager->getRepository(Association::class)
            ->findOneBy(['name' => $fakeadmin[5]]))
            ->addRoom($manager->getRepository(Room::class)
            ->findOneBy(['name' => $fakeadmin[6]]));

        return $admin;
    }
}
