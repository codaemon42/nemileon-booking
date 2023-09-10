<?php

namespace ONSBKS_Slots\Includes;

use ONSBKS_Slots\Includes\Entities\BookingsEntity;
use ONSBKS_Slots\Includes\Entities\SlotTemplates;

class Entities
{

    public function __construct()
    {
        new SlotTemplates();
        new BookingsEntity(true);
    }

}
