<?php

namespace App\Orders;

class OrderNumberGenerator
{
    public function next(): string
    {
        return 'AA'.now()->format('ymd').random_int(1000, 9999);
    }
}
