<?php

namespace ONSBKS_Slots\Includes;

class Log
{

    public static function info(string $message, ...$arg): void
    {
        Log::parse(sprintf("[INFO] $message", ...$arg));
    }

    public static function debug(string $message): void
    {
        Log::parse("[DEBUG] $message");
    }

    public static function warn(string $message): void
    {
        Log::parse("[WARN] $message");
    }

    public static function error(string $message): void
    {
        Log::parse("[ERROR] $message");
    }

    public static function parse(string $message): void
    {
        error_log($message);
    }
}
