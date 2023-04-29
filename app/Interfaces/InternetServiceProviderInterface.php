<?php

namespace App\Interfaces;

interface InternetServiceProviderInterface
{
    public function setMonth($month);

    public function calculateTotalAmount();
}
