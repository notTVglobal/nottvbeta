<x-mail::message>
# New Press Release Received

You have received a new press release.

<x-mail::button :url="$url">
View Newsroom
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
