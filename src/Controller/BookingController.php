<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Booking;
use App\Form\BookingType;
use App\Service\DateChecker;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/booking", name="booking_")
 */
class BookingController extends AbstractController
{
    /**
     * @Route("", name="index")
     * @param Request $request
     * @param DateChecker $dateChecker
     * @param EntityManagerInterface $entityManager
     * @param MailerInterface $mailer
     * @return RedirectResponse|Response
     * @throws TransportExceptionInterface
     */
    public function index(Request $request, DateChecker $dateChecker, EntityManagerInterface $entityManager, MailerInterface $mailer, PaginatorInterface $paginator)
    {
        if (!$this->getUser()) {
            $this->addFlash('warning', 'Vous devez être connecté pour accéder au service');
            return $this->redirectToRoute('app_index');
        }

        $bookings = $this->getDoctrine()
            ->getRepository(Booking::class)
            ->findAllBookingByDate();

        $pagination = $paginator->paginate(
            $bookings,
            $request->query->getInt('page', 1),
            8);

        $bookingForm = $this->createForm(BookingType::class, null, [
            'animals' => $this->getDoctrine()->getRepository(Animal::class)->findAll(),
        ]);

        $bookingForm->handleRequest($request);

        if ($bookingForm->isSubmitted() && $bookingForm->isValid()) {
            $date    = $bookingForm->getData()['atDate']->format('Y-m-d');
            $newDate = new \DateTime($date);
            $newDate->add(new \DateInterval('PT' . $bookingForm->getData()['hour'] . 'H'));

            if ($dateChecker->checkDateDisponibility($newDate)) {
                $booking = new Booking();
                $booking->setTitle($bookingForm->getData()['title']);
                $booking->setAtDate($newDate);
                $booking->setUser($this->getUser());
                $booking->setAnimal($bookingForm->getData()['animals']);

                $entityManager->persist($booking);
                $entityManager->flush();

                $email = (new Email())
                    ->from($this->getUser()->getEmail())
                    ->to($this->getParameter('mailer_to'))
                    ->subject('Votre demande de réservation')
                    ->html($this->renderView('email/send_booking.html.twig', [
                        'booking' => $booking,
                        'hour'    => $bookingForm->getData()['hour'],
                    ]));

                $mailer->send($email);

                $this->addFlash(
                    'success',
                    'Merci d\'avoir sélectionné cette date. Nous vous avons encoyé un mail de confirmation');

                return $this->redirectToRoute('booking_index');
            } else {
                $this->addFlash('warning', 'Il y a déjà une demande a cette date');
                return $this->redirectToRoute('booking_index');
            }
        }

        return $this->render('booking/index.html.twig', [
            'pagination' => $pagination,
            'form'       => $bookingForm->createView(),
        ]);
    }
}
