<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Booking;
use App\Entity\Collaborator;
use App\Entity\Image;
use App\Entity\User;
use App\Form\AnimalImageType;
use App\Form\AnimalType;
use App\Form\CollaboratorType;
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

    /**
     * @Route("/animals", name="animals")
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function animalIndex(PaginatorInterface $paginator)
    {
        $pagination = $paginator->paginate(
            $this->getDoctrine()
                ->getRepository(Animal::class)
                ->findAll(),
            1,
            10);

        return $this->render('admin/animals/animals.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/animal/delete/{id}", name="animal_delete")
     * @param Animal $animal
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function deleteAnimal(Animal $animal, EntityManagerInterface $entityManager)
    {
        foreach ($animal->getImages() as $image) {
            $entityManager->remove($image);
        }

        foreach ($animal->getBookings() as $booking) {
            $entityManager->remove($booking);
        }

        $entityManager->remove($animal);
        $entityManager->flush();
        $this->addFlash('success', 'L\'animal a bien été supprimé!');

        return $this->redirectToRoute('admin_animals');
    }

    /**
     * @Route("/animal/edit/{id}", name="animal_edit")
     * @param Animal $animal
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function editAnimal(Animal $animal, EntityManagerInterface $entityManager, Request $request)
    {
        $form = $this->createForm(AnimalType::class, $animal);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_animal_new_images', [
                'id' => $animal->getId(),
            ]);
        }

        return $this->render('admin/animals/edit.html.twig', [
            'animal' => $animal,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * @Route("/animal/new", name="animal_new")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function newAnimal(EntityManagerInterface $entityManager, Request $request)
    {
        $animal = new Animal();
        $form = $this->createForm(AnimalType::class, $animal);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($animal);
            $entityManager->flush();
            $this->addFlash('success', 'L\'animal a bien été ajouté !');

            return $this->redirectToRoute('admin_animal_new_images', [
                'id' => $animal->getId(),
            ]);
        }

        return $this->render('admin/animals/new.html.twig', [
            'form'   => $form->createView(),
        ]);
    }

    /**
     * @Route("/animal/new/images/{id}", name="animal_new_images")
     * @param Animal $animal
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function newAnimalImages(Animal $animal, EntityManagerInterface $entityManager, Request $request)
    {
        $image = new Image();

        $form = $this->createForm(AnimalImageType::class, $image);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $image->setAnimal($animal);
            $entityManager->persist($image);
            $entityManager->flush();

            $this->addFlash('success', 'l\'image a bien été supprimée! Vous pouvez continuer a en ajouter si vous le voulez !');
            return $this->redirectToRoute('admin_animal_new_images', [
                'id' => $animal->getId(),
            ]);
        }

        return $this->render('admin/animals/images.html.twig', [
            'form'   => $form->createView(),
            'images' => $animal->getImages(),
        ]);
    }

    /**
     * @Route("/animal/image/delete/{id}", name="animal_image_delete")
     * @param Image $image
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function deleteAnimalImage(Image $image, EntityManagerInterface $entityManager)
    {
        $animal = $image->getAnimal();

        $entityManager->remove($image);
        $entityManager->flush();
        $this->addFlash('success', 'L\'image a bien été supprimée!');

        return $this->redirectToRoute('admin_animal_new_images', [
            'id' => $animal->getId(),
        ]);
    }

    /**
     * @Route("/collaborators", name="collaborators")
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function collaboratorsIndex(PaginatorInterface $paginator)
    {
        $pagination = $paginator->paginate(
            $this->getDoctrine()
                ->getRepository(Collaborator::class)
                ->findAll(),
            1,
            10);

        return $this->render('admin/collaborators/collaborators.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    /**
     * @Route("/collaborator/new", name="collaborator_new")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function newCollaborator(EntityManagerInterface $entityManager, Request $request)
    {
        $collaborator = new Collaborator();
        $form = $this->createForm(CollaboratorType::class, $collaborator);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($collaborator);
            $entityManager->flush();
            $this->addFlash('success', 'Le collaborateur a bien été ajouté !');

            return $this->redirectToRoute('admin_collaborators');
        }

        return $this->render('admin/collaborators/new.html.twig', [
            'form'   => $form->createView(),
        ]);
    }

    /**
     * @Route("/collaborator/delete/{id}", name="collaborator_delete")
     * @param Collaborator $collaborator
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function deleteCollaborator(Collaborator $collaborator, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($collaborator);
        $entityManager->flush();
        $this->addFlash('success', 'Le collaborateur a bien été supprimée!');

        return $this->redirectToRoute('admin_collaborators');
    }

    /**
     * @Route("/collaborator/edit/{id}", name="collaborator_edit")
     * @param Collaborator $collaborator
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function editCollaborator(Collaborator $collaborator, EntityManagerInterface $entityManager, Request $request)
    {
        $form = $this->createForm(CollaboratorType::class, $collaborator);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_collaborators');
        }

        return $this->render('admin/collaborators/edit.html.twig', [
            'collaborator' => $collaborator,
            'form'         => $form->createView(),
        ]);
    }
}
