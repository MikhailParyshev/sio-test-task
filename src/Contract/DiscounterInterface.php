<?php

namespace App\Contract;

interface DiscounterInterface
{
    public function apply(float $price): float;
}
