<?php

namespace ONSBKS_Slots\Includes\Cron;

use ONSBKS_Slots\Includes\Cron;

class AutoCancelCronJob extends CronJob
{
    public function __construct()
    {
        parent::__construct('onsbks_auto_cancel_cron', Cron::MINUTE);

        add_action($this->cronHook, [$this, 'autoCancelCronHandler']);
    }

    public function autoCancelCronHandler(): void
    {
        // TODO: apply booking cancellation logic
    }
}
