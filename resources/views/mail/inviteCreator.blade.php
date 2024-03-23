<x-mail::message>
# Welcome {{ $name }}, to the Future of Community-Driven Media!

You, {{ $name }}, have been personally selected to become a part of something revolutionary—a vibrant creator community at notTV. Here, we're redefining the media landscape through the power of community voices and decentralization.

Your invitation comes from **{{ $from_name }}**, who sees the incredible potential in your voice and storytelling abilities to enrich our ever-growing tapestry of narratives.

> "{{ $message }}"
> ~ {{ $from_name }}

At notTV, we believe in empowering community voices through a decentralized media ecosystem, blending the innovative world of blockchain with the spirit of local storytelling. You're not just joining a platform; you're stepping into a role within a global network of autonomous community chapters, each a beacon of local empowerment, creativity, and democratic collaboration.

Your stories, your voice, and your vision have the power to inspire, engage, and make a meaningful impact. As a notTV creator, you'll leverage our comprehensive suite of tools designed for content production and community engagement, all while contributing to a rich mosaic of diverse voices that shape our media landscape.

This is your chance to be a part of a movement, 15 years in the making, that values participatory media, inclusivity, and the authentic representation of communities worldwide. Welcome to where every story finds its space, every voice finds its audience, and every creator has the tools to shape the narrative.

Here are your exclusive access details to get started:
@component('mail::panel')
- **Your Invitation Link:** [Accept Your Invitation]({{ $invite_url }})

- **Your Invite Code:** `{{ $invite_code }}`
@endcomponent

<x-mail::button :url="$invite_url">
Accept Your Invitation
</x-mail::button>
<br>
Click the link above to visit the invite page and enter your invite code to embark on your creative journey with notTV.
<br><br>
Are you ready to join us in shaping this new era of media? Together, let's build a world where media is truly by the people, for the people.
<br><br>
Warmest regards,<br>
The notTV Team
</x-mail::message>
