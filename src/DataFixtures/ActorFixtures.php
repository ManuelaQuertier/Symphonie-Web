<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ActorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {  

            $actor = new Actor();  
            $actor->setName('Nom de l\'acteur ' . $actor->getId());  
            $manager->persist($actor);  
            $manager->flush();
            $this->addReference('actor_' . $i, $actor);
        }  

        
    }
}
