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
        ['Mairie', 'mairie@gmail.com', '07.25.63.53.00'],
        ['FC Mettray', 'fcmettray@gmail.com', '07.25.63.53.54'],
        ['Natation Club Mettray', 'natationclub@gmail.com', '07.22.35.65.32'],
        ['Hockey Boys', 'hockeyboys@gmail.com', '07.46.53.51.53'],
        ['Judo Karate Club', 'judokarate@gmail.com', '07.47.83.82.73'],
        ['Amical des Gardes Champêtres', 'amicalchempetres@gmail.com', '07.65.45.43.42'],
        ['Cabinet du maire', 'cabinetmaire@mettray.fr', '02.39.85.02.48'],
        ['Service des écoles', 'ecoles@mettray.fr', '02.49.04.58.12']
    ];

    public const FAKE_ROOM_PARENT = [
        ['Terrain entier', 100, 'Grand gymnase entier', 'Daunay', NULL, 1, 1, 'Grand Gymnase'],
        ['Grand bassin', 50, 'Grand bassin, 8 lignes d\'eau', 'Guillon', NULL, 2, 1, 'Piscine Municipale'],
        ['Salle 1', 100, 'Salle des fêtes, salle 1, 1er étage', 'Cauvin', NULL, 4, 1, 'Salle des fêtes'],
        ['Salle 2', 80, 'Salle des fêtes, salle 2', 'Cauvin', NULL, 4, 1, 'Salle des fêtes'],
        ['Salle Victor Hugo', 100, 'Salle de réunion Victor Hugo, 1er étage', 'Cauvin', NULL, 4, 0, 'Hôtel de ville'],
        ['Katta', 35, 'Salle d\'entrainement', 'Cauvin', NULL, 4, 1, 'Dojo'],
        ['Battle', 70, 'Arène de tournoi', 'Cauvin', NULL, 4, 1, 'Dojo']
    ];

    public const FAKE_ROOM = [
        ['Demi terrain A', 60, 'Grand gymnase, demi terrain A', 'Daunay', 'Terrain entier', 1, 1, 'Grand Gymnase'],
        ['Demi terrain B', 40, 'Grand gymnase, demi terrain B', 'Daunay', 'Terrain entier', 1, 1, 'Grand Gymnase'],
        ['Ligne 1', 1, 'Grand bassin ligne 1', 'Guillon', 'Grand bassin', 2, 1, 'Piscine Municipale'],
        ['Ligne 2', 1, 'Grand bassin ligne 2', 'Guillon', 'Grand bassin', 2, 1, 'Piscine Municipale'],
        ['Ligne 3', 1, 'Grand bassin ligne 3', 'Guillon', 'Grand bassin', 2, 1, 'Piscine Municipale'],
        ['Ligne 4', 1, 'Grand bassin ligne 4', 'Guillon', 'Grand bassin', 2, 1, 'Piscine Municipale'],
        ['Ligne 5', 1, 'Grand bassin ligne 5', 'Guillon', 'Grand bassin', 2, 1, 'Piscine Municipale'],
        ['Ligne 6', 1, 'Grand bassin ligne 6', 'Guillon', 'Grand bassin', 2, 1, 'Piscine Municipale'],
        ['Ligne 7', 1, 'Grand bassin ligne 7', 'Guillon', 'Grand bassin', 2, 1, 'Piscine Municipale'],
        ['Ligne 8', 1, 'Grand bassin ligne 8', 'Guillon', 'Grand bassin', 2, 1, 'Piscine Municipale'],
        ['Katta 1', 50, 'Demi tatami 1', 'Bertaux', 'Katta', 5, 1, 'Dojo'],
        ['Katta 2', 50, 'Demi tatami 2', 'Bertaux', 'Katta', 5, 1, 'Dojo']
    ];

    public const FAKE_LOCATION = [
        ['Grand Gymnase', '46', 'Rue National', 'Mettray'],
        ['Piscine Municipale', '89', 'Rue du charme', 'Mettray'],
        ['Salle des fêtes', '98', 'Avenue des saules', 'Mettray'],
        ['Hôtel de ville', '37', 'Rue tabaga', 'Mettray'],
        ['Dojo', '35', 'Rue National', 'Mettray']
    ];

    public const FAKE_USER = [
        ['alan@gmail.com', ['ROLE_USER'], 'Alan', 'Daunay', '02.47.45.12.12'],
        ['adrien@gmail.com', ['ROLE_USER'], 'Adrien', 'Cauvin', '02.47.45.12.13'],
        ['dimitri@gmail.com', ['ROLE_USER'], 'Dimitri', 'Guillon', '02.47.45.12.14'],
        ['ludovic@gmail.com', ['ROLE_USER'], 'Ludovic', 'Brault', '02.47.45.12.15'],
        ['edourad@gmail.com', ['ROLE_USER'], 'Edouard', 'Vaillant', '02.47.45.12.56']
    ];

    public const FAKE_ADMIN = [
        ['kevin@gmail.com', ['ROLE_ADMIN'], 'Kevin', 'Bertaux', '02.47.45.12.16'],
        ['chloe@gmail.com', ['ROLE_ADMIN'], 'Chloe', 'Metayer', '02.47.45.12.17']
    ];

    public const FAKE_ASSO_USER = [
        ['Daunay', 'FC Mettray'],
        ['Cauvin', 'FC Mettray'],
        ['Guillon', 'Natation Club Mettray'],
        ['Brault', 'Hockey Boys'],
        ['Vaillant', 'Amical des Gardes Champêtres'],
        ['Vaillant', 'Hockey Boys'],
        ['Daunay', 'Natation Club Mettray'],
        ['Bertaux', 'Judo Karate Club'],
        ['Bertaux', 'Mairie'],
        ['Metayer', 'Mairie']
    ];

    public const FAKE_BOOKING = [
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
                ->setTelephone($fakeAsso[2]);;
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
                    $this->userPasswordHasher->hashPassword($user, 'A1azerty*')
                )
                ->setFirstname($fakeuser[2])
                ->setLastname($fakeuser[3])
                ->setPhone($fakeuser[4]);

            $manager->persist($user);
            $manager->flush();
        }

        foreach (self::FAKE_ADMIN as $fakeadmin) {

            $user = new User;

            $user
                ->setEmail($fakeadmin[0])
                ->setRoles($fakeadmin[1])
                ->setPassword(
                    $this->userPasswordHasher->hashPassword($user, 'A1azerty*')
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
                ->setUser($manager->getRepository(User::class)->findOneByLastname($fakeAssoUser[0]));

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
                ->setLocation($manager->getRepository(Location::class)->findOneByName($fakeRoomParent[7]))
                ->setVisibility($fakeRoomParent[6]);

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
                ->setLocation($manager->getRepository(Location::class)->findOneByName($fakeRoom[7]))
                ->setVisibility($fakeRoom[6]);

            $manager->persist($room);

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
                ->setAssociation($manager->getRepository(Association::class)->findOneByName($fakeBooking[7]));

            $manager->persist($roomUser);
        }
        $manager->flush();
    }
}
 