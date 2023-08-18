<?php

namespace ONSBKS_Slots\RestApi;

use ONSBKS_Slots\RestApi\Repositories\BookingRepository;

class Repositories
{

    public function __construct()
    {
        new BookingRepository();
    }
}