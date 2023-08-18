<?php

namespace ONSBKS_Slots\RestApi\Repositories;

use ONSBKS_Slots\Includes\Entities\BookingsEntity;

class BookingRepository extends BookingsEntity
{

    public function __construct()
    {
        parent::__construct(false);
    }

}