<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    const USERS = [
        [
            'email'    => 'admin@hotmail.com',
            'nickname' => 'Administrateur',
            'role'     => ['ROLE_ADMIN'],
            'password' =>
                '$argon2id$v=19$m=65536,t=4,p=1$R7T4ncLFa/gbVjeohZ9j1Q$+lNlLGsJQRmQtU3Y/CChPOfGlu03UY+ZBFLpiRRybpg',
        ],
        [
            'email'    => 'benjamin@hotmail.com',
            'nickname' => 'Benjamin',
            'role'     => ['ROLE_CUSTOMER'],
            'password' =>
                '$argon2id$v=19$m=65536,t=4,p=1$R7T4ncLFa/gbVjeohZ9j1Q$+lNlLGsJQRmQtU3Y/CChPOfGlu03UY+ZBFLpiRRybpg',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::USERS as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setNickname($userData['nickname']);
            $user->setRoles($userData['role']);
            $user->setPassword($userData['password']);

            $profil = new Profil();
            $manager->persist($profil);
            $user->setProfil($profil);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
