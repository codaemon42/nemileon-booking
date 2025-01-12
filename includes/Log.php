<?php

namespace ONSBKS_Slots\Includes;

class Log
{

    public static function info(string $message, ...$arg): void
    {
        Log::parse(sprintf("[INFO] $message", ...$arg));
    }

    public static function debug(string $message, ...$arg): void
    {
        Log::parse(sprintf("[DEBUG] $message", ...$arg));
    }

    public static function warn(string $message, ...$arg): void
    {
        Log::parse(sprintf("[WARN] $message", ...$arg));
    }

    public static function error(string $message, ...$arg): void
    {
        Log::parse(sprintf("[ERROR] $message", ...$arg));
    }

    public static function parse(string $message): void
    {
        error_log($message);
    }
}
