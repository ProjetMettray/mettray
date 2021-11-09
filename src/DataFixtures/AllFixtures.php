<?php

namespace App\DataFixtures;

use App\Entity\Room;
use App\Entity\User;
use App\Entity\Booking;
use App\Entity\Location;
use App\Entity\UserRoom;
use App\Entity\Association;
use App\Entity\AssociationUser;
use App\Entity\RoomAssociation;
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
        ['wiki',100,'salle de cours wiki','Cauvin','Arobase',2,0],
        ['cookie',50,'salle de cours cookie','Cauvin','Arobase',5,1],
        ['404',50,'salle de cours 404','Cauvin','Arobase',4,0],
        ['battle',60,'salle de cours battle','Guillon','Arobase',4,1]
    ];

    public const FAKE_LOCATION = [
        ['bat1','46','Rue pistouille','Tours'],
        ['bat2','89','Rue du charme','Nante'],
        ['bat3','98','Avenue deparla','NY'],
        ['bat4','37','Rue lol','Boston'],
        ['bat5','35','Rue boustifaille','Montreal']
    ];

    public const FAKE_USER = [
        ['a@gmail.com', ['ROLE_USER'], 'Alan', 'Daunay', '02.47.45.12.12'],
        ['b@gmail.com', ['ROLE_USER'], 'Adrien', 'Cauvin', '02.47.45.12.13'],
        ['c@gmail.com', ['ROLE_USER'], 'Dimitri', 'Guillon', '02.47.45.12.14'],
        ['d@gmail.com', ['ROLE_USER'], 'Ludovic', 'Brault', '02.47.45.12.15'],
        ['e@gmail.com', ['ROLE_USER'], 'e', 'e', '02.47.45.12.56']
    ];

    public const FAKE_ADMIN = [
        ['f@gmail.com', ['ROLE_ADMIN'], 'Kevin', 'Bertaux', '02.47.45.12.16'],
        ['g@gmail.com', ['ROLE_ADMIN'], 'g', 'g', '02.47.45.12.17']
    ];

    public const FAKE_ROOM_ASSO = [
        ['wiki','Mettray'],
        ['wiki','Lolita'],
        ['cookie','HockeyBoy'],
        ['cookie','Mettray'],
        ['404','Mettray'],
        ['wiki','HockeyBoy'],
        ['battle','Mettray'],
        ['battle','Lolita'],
        ['Arobase','Lolita']
    ];

    public const FAKE_ASSO_USER = [
        ['Daunay', 'Mettray'],
        ['Cauvin', 'Mettray'],
        ['Guillon', 'Lolita'],
        ['Brault', 'HockeyBoy'],
        ['e', 'lagoon'],
        ['e', 'HockeyBoy'],
        ['Daunay', 'Lolita']
    ];

    public const FAKE_BOOKING = [
        ['Réunion Alcooliques Anonymes','2021-10-29 23:59:59.99','2021-11-02 23:59:59.99',[],'En attente','Brault','battle','Mettray'],
        ['Match Foot','2021-11-01 23:59:59.99','2021-11-13 23:59:59.99',[],'Validé','Brault','Arobase','Lolita'],
        ['Mariage','2021-11-13 23:59:59.99','2021-11-14 12:59:59.99',[],'Supprimé','Cauvin', '404','HockeyBoy'],
        ['Anniversaire','2002-06-02 23:59:59.99','2002-06-02 23:59:59.99',[],'En attente','Daunay','battle','HockeyBoy'],
        ['Interville','2002-06-02 23:59:59.99','2002-06-02 23:59:59.99',[],'Validé','Daunay','wiki','Puchito']
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
                ;
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
                ->setPhone($fakeuser[4])
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
                ->setPhone($fakeadmin[4]);

            $manager->persist($user);
            $manager->flush();
        }

        $manager->flush();

        foreach (self::FAKE_ASSO_USER as $fakeAssoUser) {

            $assoUser = new AssociationUser();
            $assoUser
            ->setAssociation($manager->getRepository(Association::class)->findOneByName($fakeAssoUser[1]))
            ->setUser($manager->getRepository(User::class)->findOneByLastname($fakeAssoUser[0]))
            ;

            $manager->persist($assoUser);
        }
        $manager->flush();

        foreach (self::FAKE_ROOM_PARENT as $fakeRoomParent) {
            $room = new Room();
            $room->setName($fakeRoomParent[0])
                 ->setNbPlace($fakeRoomParent[1])
                 ->setDescription($fakeRoomParent[2])
                 //->addRoom($manager->getRepository(Room::class)->findOneByName($fakeRoom[3]))
                 ->setRoom($manager->getRepository(Room::class)->findOneByName($fakeRoomParent[4]))
                 ->setLocation($manager->getRepository(Location::class)->findOneByName('bat1'))
                 ->setVisibility($fakeRoomParent[5]);

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
                 ->setLocation($manager->getRepository(Location::class)->findOneByName('bat1'))
                 ->setVisibility($fakeRoom[5]);

            $manager->persist($room);
            
        }
        $manager->flush();

        foreach (self::FAKE_ROOM_ASSO as $fakeRoomAssociation) {
            $roomUser = new RoomAssociation();
            $roomUser
            ->setRoom($manager->getRepository(Room::class)->findOneByName($fakeRoomAssociation[0]))
            ->setAssociation($manager->getRepository(Association::class)->findOneByName($fakeRoomAssociation[1]))
                 ;

            $manager->persist($roomUser);
            
        }
        $manager->flush();

        foreach (self::FAKE_BOOKING as $fakeBooking) {
            $start_at = new \DateTimeImmutable($fakeBooking[1]);
            $end_at = new \DateTimeImmutable($fakeBooking[2]);

            $roomUser = new Booking();
            $roomUser
            ->setTitle($fakeBooking[0])
            ->setStartAt($start_at)
            ->setEndAt($end_at)
            ->setOptions($fakeBooking[3])
            ->setStatus($fakeBooking[4])
            ->setRoom($manager->getRepository(Room::class)->findOneByName($fakeBooking[6]))
            ->setAssociation($manager->getRepository(Association::class)->findOneByName($fakeBooking[7]))
            ;

            $manager->persist($roomUser);
        }
        $manager->flush();
    }
}
 