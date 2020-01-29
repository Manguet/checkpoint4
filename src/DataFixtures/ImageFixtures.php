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
            'url'    => 'https://www.lechorepublicain.fr/photoSRC/V15TJ1taUTgIBhVOCRAHHQ4zRSkXaldfVR5dW1sXVA49/ours-mischa-recueilli-au-zoo-refuge-la-taniere-dans-la-zone-_4544077.jpeg',
            'animal' => 'animal_0',
        ],
        "Mischa la fin" => [
            'url'    => 'https://cdn-media.rtl.fr/cache/eFQL0OPJ6dFIwtgA8IfiTw/880v587-0/online/image/2019/0913/7798329694_l-ours-mischa-a-de-nombreux-problemes-de-sante.PNG',
            'animal' => 'animal_0',
        ],
        "Pharaon : la retraite" => [
            'url'    => 'https://cdn.pixabay.com/photo/2019/03/01/10/03/cat-4027635_960_720.jpg',
            'animal' => 'animal_1',
        ],
        "Faya parmis nous" => [
            'url'    => 'https://thumbs.dreamstime.com/b/photo-de-portrait-chien-bull-terrier-avec-l-espace-bleu-vide-134717818.jpg',
            'animal' => 'animal_2',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::IMAGES as $imageName => $imageData) {
            $image = new Image();
            $image->setTitle($imageName);
            $image->setUrl($imageData['url']);
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
