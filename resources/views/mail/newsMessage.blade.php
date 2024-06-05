<x-mail::message>
# New News Message Received

You have received a new message.

## What to do next?

Please log in to the newsroom to check your inbox.

@component('mail::button', ['url' => 'https://not.tv/news-person-messages'])
Go to your inbox
@endcomponent

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>