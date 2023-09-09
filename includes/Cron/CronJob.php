<?php

namespace ONSBKS_Slots\Includes\Cron;

class CronJob implements ICronJob
{
    public string $cronHook;

    private string $recurrence;

    public function __construct($cronHook = 'onsbks_cron_hook', $recurrence = 'hourly')
    {
        $this->cronHook = $cronHook;
        $this->recurrence = $recurrence;
    }

    public function run(): void
    {
        if (!wp_next_scheduled($this->cronHook)) {
            wp_schedule_event(time(), $this->recurrence, $this->cronHook);
        }
    }



}