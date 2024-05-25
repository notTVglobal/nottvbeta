<?php
namespace App\Helpers;

class SubscriptionHelper {
  /**
   * Convert cents to dollars.
   *
   * @param int $amount
   * @return string
   */
  public static function convertToDollars($amount) {
    return number_format($amount / 100, 2, '.', '');
  }

  /**
   * Convert dollars to cents.
   *
   * @param float|string $amount
   * @return int
   */
  public static function convertToCents($amount) {
    if (strpos($amount, '.') !== false) {
      $amount = round($amount * 100);
    } else {
      $amount = $amount * 100;
    }

    return (int) $amount;
  }

  /**
   * Process subscription settings to convert prices.
   *
   * @param array $subscriptionSettings
   * @param bool $toDollars
   * @return array
   */
  public static function processSubscriptionSettings($subscriptionSettings, $toDollars = true) {
    if ($subscriptionSettings) {
      if (isset($subscriptionSettings['monthly']['price'])) {
        $subscriptionSettings['monthly']['price'] = $toDollars
            ? self::convertToDollars($subscriptionSettings['monthly']['price'])
            : self::convertToCents($subscriptionSettings['monthly']['price']);
      }
      if (isset($subscriptionSettings['yearly']['price'])) {
        $subscriptionSettings['yearly']['price'] = $toDollars
            ? self::convertToDollars($subscriptionSettings['yearly']['price'])
            : self::convertToCents($subscriptionSettings['yearly']['price']);
      }
    }

    return $subscriptionSettings;
  }
}
