<x-mail::message>
    # Hello {{ $name }},

    We're reaching out to remind you of the incredible opportunities available to you as a creator at notTV. Someone believes in your potential and wanted to remind you of the value you bring to our vibrant community.

    <x-mail::highlighted-message :message="$message" :fromName="$from_name" />

    At notTV, we empower community voices through a decentralized media ecosystem. By being part of notTV, you join a global network of autonomous community chapters, each contributing to a diverse and dynamic media landscape.

    As a notTV creator, you can:
    @component('mail::panel')
        - Engage with your audience through interactive content
        - Collaborate with other creators to produce meaningful media
        - Access advanced tools for content creation and distribution
    @endcomponent

    <x-mail::button :url="config('app.url') . '/dashboard'">
        Go to Your Dashboard
    </x-mail::button>

    We're excited to see more of your incredible work. If you need any assistance or have any questions, feel free to reach out to us.

    Together, let's continue to build a world where media is truly by the people, for the people.

    Warmest regards,<br>
    The notTV Team
</x-mail::message>