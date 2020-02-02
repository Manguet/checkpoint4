<?php

namespace App\Controller;

use App\Entity\Collaborator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/collaborator", name="collaborator_")
 */
class CollaboratorController extends AbstractController
{
    /**
     * @Route("", name="index")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $collaborators = $this->getDoctrine()
            ->getRepository(Collaborator::class)
            ->findAll();

        $pagination = $paginator->paginate(
            $collaborators,
            $request->query->getInt('page', 1),
            2
        );

        return $this->render('collaborator/index.html.twig', [
            'pagination'    => $pagination,
        ]);
    }
}
