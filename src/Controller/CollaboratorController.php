<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/collaborator", name="collaborator_")
 */
class CollaboratorController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index()
    {
        return $this->render('collaborator/index.html.twig', [
            'controller_name' => 'CollaboratorController',
        ]);
    }
}
