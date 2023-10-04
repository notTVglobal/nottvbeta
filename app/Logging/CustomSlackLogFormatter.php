<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;

class CustomSlackLogFormatter extends LineFormatter
{
    public function format(array $record): string
    {
        // Get the APP_NAME from the environment variables
        $appName = env('APP_NAME');

        // Add APP_NAME to the log message
        $record['message'] = "APP_NAME: $appName - " . $record['message'];

        return parent::format($record);
    }
}
