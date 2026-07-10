<?php

namespace App\Services;

class SimilarityService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function distance(array $a, array $b): float
    {
        $sum = 0;

        foreach ($a as $index => $value) {

            $sum += pow($value - $b[$index], 2);
        }

        return sqrt($sum);
    }
}
