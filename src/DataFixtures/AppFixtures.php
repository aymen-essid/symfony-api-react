<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Generate fixtures
        $loader = new NativeLoader();
        $objectSet = $loader->loadFile(__DIR__ . '/ORM/fixtures.yaml')->getObjects();
        foreach($objectSet as $object)
        {
            $manager->persist($object);
        }

        $manager->flush();
    }
}
