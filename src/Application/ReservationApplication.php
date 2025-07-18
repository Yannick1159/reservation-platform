<?php

namespace App\Application;

use App\Domain\ReservationDomain;
use App\Entity\Reservation;
use DateTime;
use Symfony\Component\HttpFoundation\Request;

readonly class ReservationApplication
{
    public function __construct(
        public ReservationDomain $reservationDomain
    ) {

    }

    public function createReservation(Request $request): void
    {
        $year = $request->request->get('year');
        $month = $request->request->get('month');
        $days = $request->request->get('days');
        $availableTimes = $request->request->get('availableTimes');

        $user = $request->request->get('user');

        $reservation = new Reservation();
        $reservation->setUser($user);
        $reservation->setRoom();
        $reservation->setReservationDate();
        $reservation->setStartTime();
        $reservation->setEndTime();
    }

    public function getCurrentDate(): array
    {
        $dateTimeNow = new DateTime();

        $years = $this->getAvailableYears($dateTimeNow->format('Y'));

        $months = $this->getAvailableMonths($dateTimeNow->format('m'));

        $days = $this->getAvailableDays(
            $dateTimeNow->format('Y'),
            $dateTimeNow->format('m')
        );

        $availableTimes = $this->getAvailableTimes(
            $dateTimeNow->format('Y'),
            $dateTimeNow->format('m'),
            $dateTimeNow->format('d')
        );

        return [$years, $months, $days, $availableTimes];
    }

    public function getAvailableYears(string $currentYear = null): array
    {
        if ($currentYear === null) {
            $dateTimeNow = new DateTime();
            $currentYear = (int) $dateTimeNow->format('Y');
        }

        $years = [];

        for ($i = 0; $i < 5; $i++) {
            $year = $currentYear + $i;
            $years[] = [
                'value' => $year,
                'isCurrent' => $year === $currentYear,
            ];
        }

        return $years;
    }

    public function getAvailableMonths(string $year): array
    {
        $months = [];
        $currentDate = new DateTime();
        $startDate = new DateTime("$year-01-01");
        $endDate = new DateTime("$year-12-31");

        while ($startDate <= $endDate) {
            $monthName = $startDate->format('F');
            $months[] = [
                'value' => $monthName,
                'isCurrent' => $startDate->format('Y-m') === $currentDate->format('Y-m'),
            ];
            $startDate->modify('+1 month');
        }

        return $months;
    }

    public function getAvailableDays(string $year, string $month): array
    {
        $days = [];
        $startDate = new DateTime("$year-$month-01");
        $endDate = new DateTime($startDate->format('Y-m-t'));
        $currentDate = new DateTime();

        while ($startDate <= $endDate) {
            $dayNumber = (int) $startDate->format('j');
            $dayName = $startDate->format('l');
            $isCurrent = $startDate->format('Y-m-d') === $currentDate->format('Y-m-d');

            $days[] = [
                'dayNumber' => $dayNumber,
                'dayName' => $dayName,
                'isCurrent' => $isCurrent,
            ];

            $startDate->modify('+1 day');
        }

        return $days;
    }

    public function getAvailableTimes(string $year, string $month, string $day): array
    {
        $date = new DateTime("$year-$month-$day");

        $availableTimes = $this->reservationDomain->getAvailableTimes($date);

        return $availableTimes;
    }
}
