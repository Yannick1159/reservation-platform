<?php

namespace App\Controller;

use App\Application\ReservationApplication;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReservationController extends AbstractController
{
    public function __construct(
        private readonly ReservationApplication $reservationApplication
    ) {

    }

    #[Route('/reservation', name: 'app_reservation')]
    public function setReservation(): Response
    {
        [ $years, $months, $days, $availableTimes ] = $this->reservationApplication->getCurrentDate();

        return $this->render('reservation/reservation.html.twig',
            [
                'years' => $years,
                'months' => $months,
                'days' => $days,
                'availableTimes' => $availableTimes,
            ]
        );
    }

    #[Route('/reservation/create', name: 'app_reservation_create', methods: ['POST'])]
    public function createReservation(Request $request): Response
    {
        $this->reservationApplication->createReservation($request);

        return $this->redirectToRoute('app_reservation');
    }
}
