<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function userIndex(PaginatorInterface $paginator)
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        $pagination = $paginator->paginate($users,1,10);

        $roles = [];

        foreach ($users as $user) {
            if (in_array('ROLE_ADMIN', $user->getRoles())) {
                $roles[] = 'Administrateur';
            } else {
                $roles[] = 'Utilisateur';
            }
        }

        return $this->render('admin/users/users.html.twig', [
            'pagination' => $pagination,
            'roles'      => $roles,
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
        $this->addFlash('success', 'L\'utilisateur a bien été supprimé!');

        return $this->redirectToRoute('admin_users');
    }

    /**
     * @Route("/user/edit/{id}", name="user_edit")
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function editUser(User $user, EntityManagerInterface $entityManager, Request $request)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'L\'utilisateur a bien été mis à jour !');
        }

        return $this->render('admin/users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/bookings", name="bookings")
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function bookingIndex(PaginatorInterface $paginator)
    {
         $pagination = $paginator->paginate(
            $this->getDoctrine()
                ->getRepository(Booking::class)
                ->findAllBookingByDate(),
            1,
            10);

        return $this->render('admin/bookings/bookings.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/booking/delete/{id}", name="booking_delete")
     * @param Booking $booking
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function deleteBooking(Booking $booking, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($booking);
        $entityManager->flush();
        $this->addFlash('success', 'La date a bien été supprimé!');

        return $this->redirectToRoute('admin_bookings');
    }
}
