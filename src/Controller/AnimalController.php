<?php

namespace App\Controller;

use App\Entity\Animal;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/animal", name="animal_")
 */
class AnimalController extends AbstractController
{
    /**
     * @Route("", name="index")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $animals = $this->getDoctrine()
            ->getRepository(Animal::class)
            ->findAll();

        $pagination = $paginator->paginate(
            $animals,
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('animal/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
