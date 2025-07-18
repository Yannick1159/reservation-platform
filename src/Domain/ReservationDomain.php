<?php

namespace App\Domain;

use DateTime;

class ReservationDomain
{
    public function getAvailableTimes(DateTime $currentDate): array
    {
        return [
            [
                'time_start' => '08:00',
                'time_end' => '09:00',
                'available' => true,
            ],
            [
                'time_start' => '09:00',
                'time_end' => '10:00',
                'available' => true,
            ],
            [
                'time_start' => '10:00',
                'time_end' => '11:00',
                'available' => false,
            ],
            [
                'time_start' => '11:00',
                'time_end' => '12:00',
                'available' => true,
            ],
            [
                'time_start' => '12:00',
                'time_end' => '13:00',
                'available' => true,
            ],
            [
                'time_start' => '13:00',
                'time_end' => '14:00',
                'available' => true,
            ],
            [
                'time_start' => '14:00',
                'time_end' => '15:00',
                'available' => true,
            ],
            [
                'time_start' => '15:00',
                'time_end' => '16:00',
                'available' => true,
            ],
            [
                'time_start' => '16:00',
                'time_end' => '17:00',
                'available' => true,
            ],
            [
                'time_start' => '17:00',
                'time_end' => '18:00',
                'available' => false,
            ]
        ];
    }
}
