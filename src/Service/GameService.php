<?php

namespace App\Service;

use App\Entity\Card;
use Doctrine\ORM\EntityManagerInterface;

class GameService
{
    const MAX_AS_VALUE = 11;
    const MIN_AS_VALUE = 1;

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    public function drawCards($cards)
    {
        $aviableCards = [];

        foreach ($cards as $card) {
            $aviableCards[] = $card;
        }

        $cardsDrawId = array_rand($aviableCards, 2);

        $cardsDraw = [
            'player' => $this->entityManager->getRepository(Card::class)->find($cardsDrawId[0] + 1),
            'bank'   => $this->entityManager->getRepository(Card::class)->find($cardsDrawId[1] + 1),
        ];

        return $cardsDraw;
    }

    public function scoreCard($value)
    {
        switch ($value) {
            case 'A':
                $result = '';
                break;
            case 'R':
            case 'D':
            case 'V':
                $result = 10;
                break;
            default:
                $result = intval($value);
                break;
        }

        return $result;
    }

    public function makeChoice($value)
    {
        $choice = self::MIN_AS_VALUE;

        if ( ($value + self::MAX_AS_VALUE) <= 21 ) {
            $choice = self::MAX_AS_VALUE;
        }

        return $choice;
    }

    public function isLoser($value)
    {
        $result = false;

        if ($value > 21) {
            $result = true;
        }

        return $result;
    }

    public function calculateCoins($playerScore, $bankScore)
    {
        return ($playerScore - $bankScore);
    }
}