<?php

namespace App\DataFixtures;

use App\Entity\Animal;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AnimalFixtures extends Fixture
{
    const ANIMALS = [
        'Mischa' => [
            'description'  => 'So long Mischa. L’un des derniers ours à avoir été exhibé en spectacle en France est 
                mort, mardi, au refuge La Tanière où il avait été accueilli en septembre dernier, a-t-on appris auprès 
                de plusieurs sources. Mischa avait été saisi auprès de son propriétaire, la famille Poliakov, en raison
                de son état de santé déplorable et sur décision de la ministre Elisabeth Borne.
                « De nombreuses tumeurs notamment au cerveau »
                « Il aura tout de même vécu quelques semaines une vraie vie d’ours », a déclaré l’association de 
                défense des animaux OneVoice. Malgré l’attention et les soins dont il faisait l’objet, le vieil ours 
                a finalement succombé à un état général très dégradé. Ces derniers jours, sa santé déclinait encore,
                l’animal ne supportant pas l’arrêt de son traitement antibiotique. Mischa a été endormi, mardi, afin 
                de subir de nouveaux examens. Les résultats « ont révélé de nombreuses tumeurs notamment au cerveau », 
                explique l’équipe de La Tanière. « Trop faible à l’issue des examens, l’ours ne s’est pas réveillé 
                de son anesthésie », déplorent les soigneurs.',
            'localisation' => 'Actuellement décédé',
            'specie'       => 'Ursidé',
            'race'         => 'Ours Brun',
            'color'        => 'Marron',
            'gender'       => 'Mâle',
            'image'        => 'https://img.20mn.fr/LB7TYhQgTMmthJugY4s1Iw/640x410_ours-micha-lors-dernier-spectacle-calais.jpg',
        ],
        'Pharaon' => [
            'description'  => 'Pharaon est un chat de cirque qui a été reccueilli par nos bénévoles. Suite à des 
                spectacles prolongés et de mauvais traitements, il est actuellement à la retraite et se remet de ses
                longues journées près d\'un bon feu de cheminé,',
            'localisation' => 'Pessac',
            'specie'       => 'Félin',
            'race'         => 'Européen',
            'color'        => 'Roux',
            'gender'       => 'Mâle',
            'image'        => 'https://zupimages.net/up/20/05/v9q9.jpg',
        ],
        'Faya' => [
            'description'  => 'Faya est une petite chienne qui a eu de longues journées. Elle a été saisi et 
                déposé au centre pour des soins. Elle ne vie plus que sur trois pattes à l\'heure actuelle. La 
                famille qui s\'en occupe est aux petits soins et l\'aide chaque jour dans sa kiné',
            'localisation' => 'Bordeaux',
            'specie'       => 'Canidé',
            'race'         => 'Bull Terrier',
            'color'        => 'Noir et Blanc',
            'gender'       => 'Femelle',
            'image'        => 'https://i.pinimg.com/originals/2f/92/4f/2f924fed119910f7fefd0ab47e2096f9.jpg',
        ],
    ];
    public function load(ObjectManager $manager)
    {
        $number = 0;
        $today  = new \DateTime('now');

        foreach (self::ANIMALS as $animalName => $animalData) {
            $animal = new Animal();
            $animal->setName($animalName);
            $animal->setBirthday($today);
            $animal->setDescription($animalData['description']);
            $animal->setLocalisation($animalData['localisation']);
            $animal->setSpecie($animalData['specie']);
            $animal->setRace($animalData['race']);
            $animal->setColor($animalData['color']);
            $animal->setGender($animalData['gender']);
            $animal->setImage($animalData['image']);
            $this->addReference('animal_' . $number, $animal);

            $manager->persist($animal);
            $number++;
        }

        $manager->flush();
    }
}
