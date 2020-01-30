<?php

namespace App\Controller;


use App\Entity\Animal;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="app_")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index()
    {
        $lastAnimals = $this->getDoctrine()
            ->getRepository(Animal::class)
            ->findThreeLastAnimals();

        return $this->render('index.html.twig', [
            'lastAnimals' => $lastAnimals,
        ]);
    }

    /**
     * @Route("/thanks", name="thanks")
     */
    public function thanks()
    {
        return $this->render('footer/thanks.html.twig');
    }
}
