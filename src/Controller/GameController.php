<?php

namespace App\Controller;

use App\Entity\BlackJack;
use App\Entity\Card;
use App\Form\AsType;
use App\Service\GameService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/game", name="game_")
 */
class GameController extends AbstractController
{

    const MAX_VALUE_TO_DRAW = 17;

    /**
     * @Route("", name="index")
     */
    public function index()
    {
        if ($this->getUser()) {
            return $this->render('game/index.html.twig', [
                'user' => $this->getUser()
            ]);
        } else {
            $this->addFlash('warning', 'Merci de vous connecter pour accéder à cet espace !');
            return $this->redirectToRoute('app_index');
        }
    }

    /**
     * @Route("/new", name="new")
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     * @throws \Exception
     */
    public function newGame(EntityManagerInterface $entityManager)
    {
        if ($this->getUser()->getProfil()->getTotalAmount() > 0) {
            $today = new \DateTime('now');
            $blackJack = new BlackJack();
            $blackJack->setPlayedAt($today);
            $blackJack->setAmount($this->getUser()->getProfil()->getTotalAmount());
            $blackJack->setToPay(0);
            $blackJack->setPlayerScore(0);
            $blackJack->setBankScore(0);

            $cards = $this->getDoctrine()
                ->getRepository(Card::class)
                ->findAll();

            foreach ($cards as $card) {
                $blackJack->addCard($card);
            }

            $entityManager->persist($blackJack);
            $entityManager->persist($this->getUser()->getProfil()->addBlackjack($blackJack));

            $entityManager->flush();
            return $this->redirectToRoute('game_choice', [
                'id' => $blackJack->getId(),
            ]);

        } else {
            $this->addFlash('warning', 'Vous n\'avez plus assez de WildCoins pour jouer.');
            return $this->redirectToRoute('game_index');
        }
    }

    /**
     * @Route("/choice/{id}", name="choice")
     * @param BlackJack $blackJack
     * @return Response
     */
    public function choiceAction(BlackJack $blackJack)
    {
        return $this->render('game/choice.html.twig', [
            'user'      => $this->getUser(),
            'blackJack' => $blackJack,
        ]);
    }

    /**
     * @Route("/draw/{id}", name="draw")
     * @param BlackJack $blackJack
     * @param GameService $gameService
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function Draw(BlackJack $blackJack, GameService $gameService, EntityManagerInterface $entityManager)
    {
        $drawCards   = $gameService->drawCards($blackJack->getCards());
        $playerCard  = $drawCards['player'];
        $playerScore = $gameService->scoreCard($playerCard->getValue());
        $bankCard    = '';

        if (($blackJack->getBankScore() < $blackJack->getPlayerScore()) || ($blackJack->getBankScore() < self::MAX_VALUE_TO_DRAW)) {
            $bankCard    = $drawCards['bank'];
            $bankScore   = $gameService->scoreCard($bankCard->getValue());

        } else {
            $bankScore = 0;
        }

        if ($playerScore === '') {
            if ($bankScore === '') {
                $bankScore = $gameService->makeChoice($blackJack->getBankScore());
            }
            $blackJack->setBankScore($blackJack->getBankScore() + $bankScore);
            $entityManager->flush();

            return $this->redirectToRoute('game_choice_value', [
                'id' => $blackJack->getId(),
            ]);
        }

        if ($bankScore === '') {
            $bankScore = $gameService->makeChoice($blackJack->getBankScore());
        }

        $blackJack->setPlayerScore($blackJack->getPlayerScore() + $playerScore);
        $blackJack->setBankScore($blackJack->getBankScore() + $bankScore);

        $entityManager->flush();

        if ($gameService->isLoser($blackJack->getPlayerScore())) {
            return $this->redirectToRoute('game_loser', [
                'id' => $blackJack->getId(),
            ]);
        } elseif ($gameService->isLoser($blackJack->getBankScore())) {
            return $this->redirectToRoute('game_winner', [
                'id' => $blackJack->getId(),
            ]);
        }

        return $this->render('game/drawResult.html.twig', [
            'user'        => $this->getUser(),
            'blackJack'   => $blackJack,
            'playerCard'  => $playerCard,
            'bankCard'    => $bankCard,
        ]);
    }

    /**
     * @Route("/choice-value/{id}", name="choice_value")
     * @param BlackJack $blackJack
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function choiceValue(BlackJack $blackJack, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(AsType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $selectedValue = $form->getData()['choice'];
            $blackJack->setPlayerScore($blackJack->getPlayerScore() + $selectedValue);

            $entityManager->flush();
            return $this->redirectToRoute('game_choice', [
                'id' => $blackJack->getId(),
            ]);
        }

        return $this->render('game/as.html.twig', [
            'id'        => $blackJack->getId(),
            'user'      => $this->getUser(),
            'blackJack' => $blackJack,
            'form'      => $form->createView(),
        ]);
    }

    /**
     * @Route("/loser/{id}", name="loser")
     * @param BlackJack $blackJack
     * @param GameService $gameService
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function looser(BlackJack $blackJack, GameService $gameService, EntityManagerInterface $entityManager)
    {
        $blackJack->setToPay(
            $gameService->calculateCoins(
                $blackJack->getPlayerScore(),
                $blackJack->getBankScore()
            )
        );

        $this->getUser()->getProfil()->setTotalAmount(
            $this->getUser()->getProfil()->getTotalAmount() + $blackJack->getToPay()
        );

        $entityManager->flush();

        return $this->render('game/lose.html.twig', [
            'id'        => $blackJack->getId(),
            'user'      => $this->getUser(),
            'blackJack' => $blackJack,
        ]);
    }

    /**
     * @Route("/winner/{id}", name="winner")
     * @param BlackJack $blackJack
     * @param GameService $gameService
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function winner(BlackJack $blackJack, GameService $gameService, EntityManagerInterface $entityManager)
    {
        $blackJack->setToPay(
            $gameService->calculateCoins(
                $blackJack->getPlayerScore(),
                $blackJack->getBankScore()
            )
        );

        $this->getUser()->getProfil()->setTotalAmount(
            $this->getUser()->getProfil()->getTotalAmount() - $blackJack->getToPay()
        );

        $entityManager->flush();

        return $this->render('game/win.html.twig', [
            'id'        => $blackJack->getId(),
            'user'      => $this->getUser(),
            'blackJack' => $blackJack,
        ]);
    }

    /**
     * @Route("/result/{id}", name="result")
     * @param BlackJack $blackJack
     * @param GameService $gameService
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function result(BlackJack $blackJack, GameService $gameService, EntityManagerInterface $entityManager)
    {
        if ($blackJack->getBankScore() < $blackJack->getPlayerScore() && $blackJack->getBankScore() <21) {
            $drawCards   = $gameService->drawCards($blackJack->getCards());
            $bankCard    = $drawCards['bank'];
            $bankScore   = $gameService->scoreCard($bankCard->getValue());
            if ($bankScore === '') {
                $bankScore = $gameService->makeChoice($blackJack->getBankScore());
            }

            $blackJack->setBankScore($blackJack->getBankScore() + $bankScore);

            $entityManager->flush();
            return $this->redirectToRoute('game_result', [
                'id' => $blackJack->getId(),
            ]);
        }

        if ( ($gameService->isLoser($blackJack->getBankScore()) && $blackJack->getPlayerScore() <= 21) ||
            ( $blackJack->getBankScore() <= 21 && $blackJack->getPlayerScore() <= 21 && $blackJack->getPlayerScore() > $blackJack->getBankScore())) {
            return $this->redirectToRoute('game_winner', [
                'id' => $blackJack->getId(),
            ]);
        } else {
            return $this->redirectToRoute('game_loser', [
                'id' => $blackJack->getId(),
            ]);
        }
    }

    /**
     * @Route("/games", name="games")
     * @return Response
     */
    public function historique()
    {
        $profil = $this->getUser()->getProfil();

        return $this->render('game/games.html.twig', [
            'profil' => $profil,
        ]);
    }
}
