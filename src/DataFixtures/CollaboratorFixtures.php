<?php

namespace App\DataFixtures;

use App\Entity\Collaborator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CollaboratorFixtures extends Fixture
{
    const COLLABORATORS = [
        'Anne Quedeville' => [
            'localisation' => 'Bordeaux',
            'birthday'     => '19-12-1986 09:00:00',
            'description'  => 'Je suis Wiki et toujours avec ma Pédia !',
            'role'         => 'Chercheuse',
            'image'        => 'Anne_Quiedeville.jpg',
        ],
        'Celine Godichon' => [
            'localisation' => 'Bordeaux',
            'birthday'     => '15-04-1989 09:00:00',
            'description'  => 'Je suis Pédia et toujours avec ma Wiki !',
            'role'         => 'Femme talentueuse de caractère',
            'image'        => 'celine.jpg',
        ],
        'Benjamin Manguet' => [
            'localisation' => 'Pessac',
            'birthday'     => '15-03-1989 09:00:00',
            'description'  => 'Ancien ASV en reconversion professionnel !',
            'role'         => 'Soin aux animaux',
            'image'        => 'Profil.jpg',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::COLLABORATORS as $collaboratorName => $data) {
            $collaborator = new Collaborator();
            $collaborator->setLocalisation($data['localisation']);
            $collaborator->setName($collaboratorName);
            $collaborator->setBirthday(new \DateTime($data['birthday']));
            $collaborator->setDescription($data['description']);
            $collaborator->setRole($data['role']);
            $collaborator->setImage($data['image']);

            $manager->persist($collaborator);
        }

        $manager->flush();
    }
}
