<?php

namespace App\Helpers;

use Carbon\Carbon;

class GenerateRandomNumber
{
        public function uniqueRandomNumber($serialString, $numDigits): string
        {
            $numbers = range(0, 9);
            shuffle($numbers);
            $randNumber = '';
            $year = Carbon::now()->year;
            for ($i = 0; $i < $numDigits; $i++) {
                $randNumber .= $numbers[$i];
            }
            return $serialString.$year.'-'.$randNumber;
        }
}


