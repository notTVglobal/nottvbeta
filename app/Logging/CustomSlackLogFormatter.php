<?php

namespace App\Logging;

use Monolog\Formatter\JsonFormatter;
use Monolog\LogRecord;

class CustomSlackLogFormatter extends JsonFormatter
{
  public function format(LogRecord $record): string
  {
    // Get the APP_NAME from the environment variables
    $appName = env('APP_NAME');

    // Prepare the message as a Slack attachment
    $slackMessage = [
        'attachments' => [
            [
                'text' => "[" . $appName . "] " . $record->message,
                'color' => '#36a64f', // You can set color for different log levels
            ]
        ]
    ];

    // Convert the LogRecord to an array and override the formatted data
    $recordArray = $record->toArray();
    $recordArray['formatted'] = json_encode($slackMessage);

    // Return the custom formatted message
    return $recordArray['formatted'];
  }
}
