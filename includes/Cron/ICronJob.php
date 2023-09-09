<?php

namespace ONSBKS_Slots\Includes\Cron;

interface ICronJob
{
    public function run(): void;
}