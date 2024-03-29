<?php

namespace App\Listeners;

use App\Events\PushDataFetched;
use App\Services\PushDestinationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdatePushDestinations
{

  protected PushDestinationService $service;

    /**
     * Create the event listener.
     *
     * @return void
     */
  public function __construct(PushDestinationService $service)
  {
    $this->service = $service;
  }


    /**
     * Handle the event.
     *
     * @param PushDataFetched $event
     * @return void
     */
  public function handle(PushDataFetched $event): void {
    // Resolve PushDestinationService from the service container
    $this->service->updateDestinations($event->pushData);

  }
}
