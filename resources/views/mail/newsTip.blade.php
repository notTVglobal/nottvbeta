<x-mail::message>
# New News Tip Received

You have received a new news tip.

## What to do next?

Please log in to the newsroom to check your inbox.

@component('mail::button', ['url' => 'https://not.tv/news-person-messages'])
Go to your inbox
@endcomponent

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>