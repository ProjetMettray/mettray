<?php

namespace App\DataFixtures;

use App\Entity\Room;
use App\Entity\User;
use App\Entity\Location;
use App\Entity\UserRoom;
use App\Entity\Association;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AllFixtures extends Fixture
{
    public const FAKE_ASSO = [
        ['Mettray','ad@ad.fr','0725635354'],
        ['Lolita','ed@ed.fr','07223565325'],
        ['HockeyBoy','dd@dd.fr','0746535153'],
        ['Puchito','bd@bd.fr','0747838273'],
        ['lagoon','ld@ld.fr','0765454342']
    ];

    public const FAKE_ROOM_PARENT = [
        ['Arobase',100,'salle de cours arobase','Daunay',NULL,1]
    ];

    public const FAKE_ROOM = [
        ['wiki',100,'salle de cours wiki','Cauvin','Arobase',2],
        ['cookie',50,'salle de cours cookie','Cauvin','Arobase',5],
        ['404',50,'salle de cours 404','Cauvin','Arobase',4],
        ['battle',60,'salle de cours battle','Guillon','Arobase',4]
    ];

    public const FAKE_LOCATION = [
        ['bat1','46','Rue pistouille','Tours'],
        ['bat2','89','Rue du charme','Nante'],
        ['bat3','98','Avenue deparla','NY'],
        ['bat4','37','Rue lol','Boston'],
        ['bat5','35','Rue boustifaille','Montreal']
    ];

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

    public const FAKE_ROOM_USER = [
        ['wiki','Daunay'],
        ['wiki','Guillon'],
        ['cookie','Cauvin'],
        ['cookie','Daunay'],
        ['404','Cauvin'],
        ['wiki','Daunay'],
        ['battle','Daunay'],
        ['battle','Brault'],
        ['Arobase','Brault']
    ];

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::FAKE_ASSO as $fakeAsso) {
            $association = new Association();
            $association->setName($fakeAsso[0])
                ->setEmail($fakeAsso[1])
                ->setTelephone($fakeAsso[2]);
            $manager->persist($association);
        }
        $manager->flush();

        foreach (self::FAKE_LOCATION as $fakeLoc) {
            $location = new Location();
            $location->setName($fakeLoc[0])
                ->setCp($fakeLoc[1])
                ->setRoad($fakeLoc[2])
                ->setCity($fakeLoc[3]);
            $manager->persist($location);
            
        }
        $manager->flush();

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
                ->setPhone($fakeuser[4]);
                //->addAssociation(
                //    $manager->getRepository(Association::class)
                //    ->findOneByName($fakeuser[5]));

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
                ->addAssociation(
                    $manager->getRepository(Association::class)
                    ->findOneByName($fakeadmin[5]));

            $manager->persist($user);
            $manager->flush();
        }

        $manager->flush();

        foreach (self::FAKE_ROOM_PARENT as $fakeRoomParent) {
            $room = new Room();
            $room->setName($fakeRoomParent[0])
                 ->setNbPlace($fakeRoomParent[1])
                 ->setDescription($fakeRoomParent[2])
                 //->addRoom($manager->getRepository(Room::class)->findOneByName($fakeRoom[3]))
                 ->setRoom($manager->getRepository(Room::class)->findOneByName($fakeRoomParent[4]))
                 ->setLocation($manager->getRepository(Location::class)->findOneByName('bat1'));

            $manager->persist($room);
            
        }
        $manager->flush();

        foreach (self::FAKE_ROOM as $fakeRoom) {
            $room = new Room();
            $room->setName($fakeRoom[0])
                 ->setNbPlace($fakeRoom[1])
                 ->setDescription($fakeRoom[2])
                 //->addRoom($manager->getRepository(Room::class)->findOneByName($fakeRoom[3]))
                 ->setRoom($manager->getRepository(Room::class)->findOneByName($fakeRoom[4]))
                 ->setLocation($manager->getRepository(Location::class)->findOneByName('bat1'));

            $manager->persist($room);
            
        }
        $manager->flush();

        foreach (self::FAKE_ROOM_USER as $fakeRoomUser) {
            $roomUser = new UserRoom();
            $roomUser
            ->setRoom($manager->getRepository(Room::class)->findOneByName($fakeRoomUser[0]))
            ->setUser($manager->getRepository(User::class)->findOneByLastname($fakeRoomUser[1]))
                 ;

            $manager->persist($roomUser);
            
        }
        $manager->flush();
    }
}
