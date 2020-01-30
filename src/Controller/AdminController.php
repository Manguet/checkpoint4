<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/users", name="users")
     */
    public function userIndex()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        $roles = [];

        foreach ($users as $user) {
            if (in_array('ROLE_ADMIN', $user->getRoles())) {
                $roles[] = 'Administrateur';
            } else {
                $roles[] = 'Utilisateur';
            }
        }

        return $this->render('admin/users/users.html.twig', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete")
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function deleteUser(User $user, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('success', 'L\'utilisateur a bein été supprimé!');

        return $this->redirectToRoute('admin_users');
    }

    /**
     * @Route("/user/edit/{id}", name="user_edit")
     */
    public function editUser(User $user, EntityManagerInterface $entityManager)
    {

    }
}
