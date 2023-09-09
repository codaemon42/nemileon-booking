<?php

namespace ONSBKS_Slots\Includes;

use ONSBKS_Slots\Includes\Cron\AutoCancelCronJob;

class Cron
{

    const MINUTE = 'everyminute';

    public function __construct()
    {
        add_filter( 'cron_schedules', [$this, 'everyMinuteCronSchedule'] );
    }

    public function initAllCronJobs(): void
    {
        $autoCancelCronJob = new AutoCancelCronJob();
        $autoCancelCronJob->run();
    }


    public function everyMinuteCronSchedule( $schedules ) {
        $schedules[self::MINUTE] = array(
            'interval'  => 60, // time in seconds
            'display'   => 'Every Minute'
        );
        return $schedules;
    }
}
