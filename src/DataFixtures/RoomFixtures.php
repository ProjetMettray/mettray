<?php 

namespace App\DataFixtures ;

use App\Entity\Room;
use App\Entity\Location;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\AssociationFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;


class RoomFixtures extends Fixture {



    public const FAKE_ROOM= [
        ['Arobase',100,'salle de cours arobase',1,2 , 3,4,1],
        ['wiki',100,'salle de cours wiki',2,1,3,4,2],
        ['cookie',50,'salle de cours cookie',1,2,2,4,5],
        ['404',50,'salle de cours 404',1,1, 2,2,4],
        ['battle',60,'salle de cours battle',2,1,3,1,4]
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
                 ->addEvent($fakeRoom[6])
                 ->setLocationId($fakeRoom[7]);
            $manager->persist($location);
            
        }
        $manager->flush();
    }
}