<?php 

namespace App\DataFixtures ;

use App\Entity\Room;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\Location;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\AssociationFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class RoomFixtures extends Fixture implements DependentFixtureInterface {



    public const FAKE_ROOM= [
        ['Arobase',100,'salle de cours arobase','Daunay',2 , 3,4,1],
        ['wiki',100,'salle de cours wiki','Cauvin',1,3,2],
        ['cookie',50,'salle de cours cookie','Cauvin',2,2,5],
        ['404',50,'salle de cours 404','Cauvin',1, 2,4],
        ['battle',60,'salle de cours battle','Guillon',1,3,4]
    ];
    public function load(ObjectManager $manager)
    {
        foreach (self::FAKE_ROOM as $fakeRoom) {
            $room = new Room();
            $room->setName($fakeRoom[0])
                 ->setNbPlace($fakeRoom[1])
                 ->setDescription($fakeRoom[2])
                 ->addRoomHasUser($fakeRoom[3])
                 ->setRoom($fakeRoom[4])
                 ->addRoomId($fakeRoom[5])
                 ->setLocationId($fakeRoom[6]);

            $manager->persist($room);
            
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}