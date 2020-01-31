<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/game", name="game_")
 */
class GameController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index()
    {

        return $this->render('game/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}
