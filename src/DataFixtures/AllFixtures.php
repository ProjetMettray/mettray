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
        ['FC Mettray', 'fcmettray@gmail.com', '07.25.63.53.54'],
        ['Natation Club Mettray', 'natationclub@gmail.com', '07.22.35.65.32'],
        ['Hockey Boys', 'hockeyboys@gmail.com', '07.46.53.51.53'],
        ['Judo Karate Club', 'judokarate@gmail.com', '07.47.83.82.73'],
        ['Amical des Gardes Champêtres', 'amicalchempetres@gmail.com', '07.65.45.43.42'],
        ['Cabinet du maire', 'cabinetmaire@mettray.fr', '02.39.85.02.48'],
        ['Service des écoles', 'ecoles@mettray.fr', '02.49.04.58.12']
    ];

    public const FAKE_ROOM_PARENT = [
        ['Terrain entier', 100, 'Grand gymnase entier', 'Daunay', NULL, 1, 1],
        ['Grand bassin', 50, 'Grand bassin, 8 lignes d\'eau', 'Guillon', NULL, 2, 1],
        ['Salle 1', 100, 'Salle des fêtes, salle 1, 1er étage', 'Cauvin', NULL, 4, 1],
        ['Salle 2', 80, 'Salle des fêtes, salle 2', 'Cauvin', NULL, 4, 1],
        ['Salle Victor Hugo', 100, 'Salle de réunion Victor Hugo, 1er étage', 'Cauvin', NULL, 4, 0],
        ['Katta', 35, 'Salle d\'entrainement', 'Cauvin', NULL, 4, 1],
        ['Battle', 70, 'Arène de tournoi', 'Cauvin', NULL, 4, 1]
    ];

    public const FAKE_ROOM = [
        ['Demi terrain A', 60, 'Grand gymnase, demi terrain A', 'Daunay', 'Terrain entier', 1, 1],
        ['Demi terrain B', 40, 'Grand gymnase, demi terrain B', 'Daunay', 'Terrain entier', 1, 1],
        ['Ligne 1', 1, 'Grand bassin ligne 1', 'Guillon', 'Grand bassin', 2, 1],
        ['Ligne 2', 1, 'Grand bassin ligne 2', 'Guillon', 'Grand bassin', 2, 1],
        ['Ligne 3', 1, 'Grand bassin ligne 3', 'Guillon', 'Grand bassin', 2, 1],
        ['Ligne 4', 1, 'Grand bassin ligne 4', 'Guillon', 'Grand bassin', 2, 1],
        ['Ligne 5', 1, 'Grand bassin ligne 5', 'Guillon', 'Grand bassin', 2, 1],
        ['Ligne 6', 1, 'Grand bassin ligne 6', 'Guillon', 'Grand bassin', 2, 1],
        ['Ligne 7', 1, 'Grand bassin ligne 7', 'Guillon', 'Grand bassin', 2, 1],
        ['Ligne 8', 1, 'Grand bassin ligne 8', 'Guillon', 'Grand bassin', 2, 1],
        ['Katta 1', 50, 'Demi tatami 1', 'Bertaux', 'Katta', 5, 1],
        ['Katta 2', 50, 'Demi tatami 2', 'Bertaux', 'Katta', 5, 1]
    ];

    public const FAKE_LOCATION = [
        ['Grand Gymnase', '37390', 'Rue National', 'Mettray'],
        ['Piscine Municiapl', '37390', 'Rue du charme', 'Mettray'],
        ['Salle des fêtes', '37390', 'Avenue des saules', 'Mettray'],
        ['Hôtel de ville', '37390', 'Rue tabaga', 'Mettray'],
        ['Dojo', '37390', 'Rue National', 'Mettray']
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

    public const FAKE_ROOM_ASSO = [
        ['Demi terrain A', 'FC Mettray'],
        ['Demi terrain B', 'FC Mettray'],
        ['Grand bassin', 'Natation Club Mettray'],
        ['Ligne 1', 'Natation Club Mettray'],
        ['Ligne 2', 'Natation Club Mettray'],
        ['Ligne 3', 'Natation Club Mettray'],
        ['Ligne 4', 'Natation Club Mettray'],
        ['Ligne 5', 'Natation Club Mettray'],
        ['Ligne 6', 'Natation Club Mettray'],
        ['Ligne 7', 'Natation Club Mettray'],
        ['Ligne 8', 'Natation Club Mettray'],
        ['Terrain entier', 'Hockey Boys'],
        ['Katta', 'FC Mettray'],
        ['Katta 1', 'FC Mettray'],
        ['wiki', 'Hockey Boys'],
        ['Katta', 'Judo Karate Club'],
        ['Battle', 'Judo Karate Club'],
        ['Terrain entier', 'Natation Club Mettray']
    ];

    public const FAKE_ASSO_USER = [
        ['Daunay', 'FC Mettray'],
        ['Cauvin', 'FC Mettray'],
        ['Guillon', 'Natation Club Mettray'],
        ['Brault', 'Hockey Boys'],
        ['Vaillant', 'Amical des Gardes Champêtres'],
        ['Vaillant', 'Hockey Boys'],
        ['Daunay', 'Natation Club Mettray'],
        ['Bertaux', 'Judo Karate Club']
    ];

    public const FAKE_BOOKING = [
        ['Match amical', '2021-11-10 08:00:00.00', '2021-11-10 18:00:00.00', [], 'En attente', 'Brault', 'Demi terrain A', 'FC Mettray'],
        ['Entrainement', '2021-11-05 06:00:00.00', '2021-11-05 12:30:00.00', [], 'Validé', 'Brault', 'Terrain entier', 'Natation Club Mettray'],
        ['Match retour', '2021-11-13 13:00:00.00', '2021-11-13 17:30:00.00', [], 'Supprimé', 'Cauvin', 'Katta 1', 'Hockey Boys'],
        ['Anniversaire', '2021-12-02 15:30:00.00', '2021-12-02 23:30:00.00', [], 'Validé', 'Daunay', 'Battle', 'Hockey Boys'],
        ['Interville', '2021-12-05 07:00:00.00', '2021-12-05 19:00:00.00', [], 'Validé', 'Daunay', 'Katta', 'Judo Karate Club']
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
                    $this->userPasswordHasher->hashPassword($user, 'a')
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
                ->setLocation($manager->getRepository(Location::class)->findOneByName('Grand Gymnase'))
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
                ->setLocation($manager->getRepository(Location::class)->findOneByName('Grand Gymnase'))
                ->setVisibility($fakeRoom[6]);

            $manager->persist($room);

        }
        $manager->flush();

        foreach (self::FAKE_ROOM_ASSO as $fakeRoomAssociation) {
            $roomUser = new RoomAssociation();
            $roomUser
                ->setRoom($manager->getRepository(Room::class)->findOneByName($fakeRoomAssociation[0]))
                ->setAssociation($manager->getRepository(Association::class)->findOneByName($fakeRoomAssociation[1]));

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
                ->setAssociation($manager->getRepository(Association::class)->findOneByName($fakeBooking[7]));

            $manager->persist($roomUser);
        }
        $manager->flush();
    }
}
 