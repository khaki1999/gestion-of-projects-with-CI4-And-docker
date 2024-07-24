<?php

namespace App\Validation;

class CustomRules
{
    public function valid_date_of_birth(string $dateOfBirth): bool
    {
        $date = \DateTime::createFromFormat('Y-m-d', $dateOfBirth);
        if ($date === false) {
            return false;
        }

        if ($date > new \DateTime()) {
            return false;
        }

        return true;
    }
}
