<?php

namespace App\DataFixtures;

use App\Entity\Program;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 6; $i++) {

            $program = new Program();
            $program->setTitle('title' . rand(1, 6));
            $program->setSummary('Summary' . rand(1, 6));
            $program->setCategory($this->getReference('category_' . rand(1, 4)));
            for ($i=1; $i < 10; $i++) {

                $program->addActor($this->getReference('actor_' . $i));
    
            }

            $manager->persist($program);
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            ActorFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
