<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ImageFixtures extends Fixture implements DependentFixtureInterface
{
    const IMAGES = [
        "Mischa en box" => [
            'image'    => 'mischa-box.jpg',
            'animal' => 'animal_0',
        ],
        "Mischa la fin" => [
            'image'    => 'mischa-sante.PNG',
            'animal' => 'animal_0',
        ],
        "Pharaon : la retraite" => [
            'image'    => 'pharaon.webp',
            'animal' => 'animal_1',
        ],
        "Faya parmis nous" => [
            'image'    => 'faya.jpg',
            'animal' => 'animal_2',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::IMAGES as $imageName => $imageData) {
            $image = new Image();
            $image->setTitle($imageName);
            $image->setImage($imageData['image']);
            $image->setAnimal($this->getReference($imageData['animal']));

            $manager->persist($image);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [AnimalFixtures::class];
    }
}
