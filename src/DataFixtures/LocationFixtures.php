<?php 

namespace App\DataFixtures ;

use App\Entity\Location;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\AssociationFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;


class LocationFixtures extends Fixture {



    public const FAKE_LOCATION = [
        ['bat1','46','Rue pistouille','Tours'],
        ['bat2','89','Rue du charme','Nante'],
        ['bat3','98','Avenue deparla','NY'],
        ['bat4','37','Rue lol','Boston'],
        ['bat5','35','Rue boustifaille','Montreal']
    ];
    public function load(ObjectManager $manager)
    {
        foreach (self::FAKE_LOCATION as $fakeLoc) {
            $location = new Location();
            $location->setName($fakeLoc[0])
                ->setCp($fakeLoc[1])
                ->setRoad($fakeLoc[2])
                ->setCity($fakeLoc[3]);
            $manager->persist($location);
            
        }
        $manager->flush();
    }
}