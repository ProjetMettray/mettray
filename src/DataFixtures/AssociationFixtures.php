<?php
namespace App\DataFixtures ;

use App\Entity\Association;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AssociationFixtures extends Fixture {
    public const FAKE_ASSO = [
        ['Mettray','ad@ad.fr','0725635354'],
        ['Lolita','ed@ed.fr','07223565325'],
        ['HockeyBoy','dd@dd.fr','0746535153'],
        ['Puchito','bd@bd.fr','0747838273'],
        ['lagoon','ld@ld.fr','0765454342']
    ];
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
    }
}