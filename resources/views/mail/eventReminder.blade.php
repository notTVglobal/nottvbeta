@php
    use Carbon\Carbon;
@endphp

<x-mail::message>
# Upcoming Broadcast Reminder

You have an upcoming event:

**Broadcast Name:** {{ $eventDetails['name'] }}

**Date:** {{ Carbon::parse($eventDetails['broadcastDate'])->format('l, F j, Y \a\t g:ia T') }}

**Category:** {{ $eventDetails['category']['name'] }}

**Sub-Category:** {{ $eventDetails['subCategory']['name'] }}

<div style="margin: 20px 0;">
@component('mail::button', ['url' => $eventDetails['url']])
View Broadcast
@endcomponent
</div>

**Description:** {!! $eventDetails['description'] !!}

---

Enjoy,<br>
{{ config('app.name') }}
</x-mail::message>
