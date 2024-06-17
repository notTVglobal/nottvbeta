<?php

namespace App\Traits;

trait EmojiConversion
{
  protected function convertToEmojis(string $text): string
  {

    $emojis = [
        '<3'  => '❤️',
        '</3' => '💔',
        ':-)' => '😀',
        ':)'  => '😀',
        ':-(' => '😞',
        ':('  => '😞',
        ';-)' => '😉',
        ';)'  => '😉',
        ':D'  => '😃',
        ':-D' => '😃',
        ':P'  => '😛',
        ':-P' => '😛',
        ':o'  => '😮',
        ':-o' => '😮',
        ':O'  => '😲',
        ':-O' => '😲',
        ':-|' => '😐',
        ':|'  => '😐',
        ':-/' => '😕',
        ':-\\' => '😕',
        ':/'  => '😕',
        ':\\' => '😕',
        ':-*' => '😗',
        ':*'  => '😘',
        ':-X' => '🤐',
        ':X'  => '🤐',
        ':-$' => '😳',
        ':$'  => '😳',
        ':-@' => '😡',
        ':@'  => '😡',
        ':#'  => '😶',
        'O:-)' => '😇',
        'O:)'  => '😇',
        '3:-)' => '😈',
        '3:)'  => '😈',
        ':,('  => '😢',
        ':\'(' => '😢',
        ':,)'  => '😂',
        ':\')' => '😂',
        ':^)'=> '😊',
        ':-^'=> '😊',
        '>:('=> '😠',
        '>:O'=> '😲',
        ':S' => '😖',
        ':-S'=> '😖',
        'D:' => '😧',
        'D;'=> '😧',
        ':3' => '😊',
        ':-3'=> '😊',
    ];

    // Replace each instance of the text pattern with its corresponding emoji
    foreach ($emojis as $pattern => $emoji) {
      $text = str_replace($pattern, $emoji, $text);
    }

    return $text;
  }
}
