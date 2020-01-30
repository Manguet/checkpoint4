<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function index(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = (new Email())
                ->from($form->getData()['email'])
                ->to($this->getParameter('mailer_to'))
                ->subject('Vous avez reçu un message')
                ->html($this->renderView('email/contact.html.twig', [
                    'contactData' => $form->getData(),
                ]));

            $mailer->send($email);

            $this->addFlash(
                'success',
                'Votre message a bien été envoyé. Nous reprenons contact avec vous au plus vite !');

            return $this->redirectToRoute("app_index");

        }

        return $this->render('contact/index.html.twig', [
            'form'     => $form->createView(),
        ]);
    }
}
