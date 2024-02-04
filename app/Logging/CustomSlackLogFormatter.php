<?php

namespace App\Logging;

use Monolog\Formatter\JsonFormatter;

class CustomSlackLogFormatter extends JsonFormatter
{
  public function format(array $record): string
  {
    // Get the APP_NAME from the environment variables
    $appName = env('APP_NAME');

    // Prepare the message as a Slack attachment
    $slackMessage = [
        'attachments' => [
            [
                'text' => "[" . $appName . "] " . $record['message'],
                'color' => '#36a64f', // You can set color for different log levels
            ]
        ]
    ];

    // Override the record's formatted data
    $record['formatted'] = json_encode($slackMessage);

    return parent::format($record);
  }
}
