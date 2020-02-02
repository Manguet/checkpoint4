<?php

namespace App\Service;

use App\Entity\Booking;
use Doctrine\ORM\EntityManagerInterface;

class DateChecker
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    function checkDateDisponibility($date): bool
    {
        $bookings = $this->em->getRepository(Booking::class)->findAll();
        $usedDate = [];
        $result   = false;

        foreach ($bookings as $booking) {
            $usedDate[] = $booking->getAtDate();
        }

        if (!in_array($date, $usedDate)) {
            $result = true;
        }

        return $result;
    }

}