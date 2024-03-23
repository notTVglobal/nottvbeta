@props([
    'url',
    'color' => 'primary', // Ensure this class is defined in your email CSS (inlined)
    'align' => 'center',
])

<!-- Start of the Button Component -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="{{ $align }}" style="margin: auto;">
    <tr>
        <td style="border-radius: 5px; background: {{ $color == 'primary' ? '#3490dc' : '#ffffff' }}; text-align: center;">
            <a href="{{ $url }}" target="_blank" rel="noopener" style="background: {{ $color == 'primary' ? '#3490dc' : '#ffffff' }}; border: 1px solid {{ $color == 'primary' ? '#3490dc' : '#ffffff' }}; font-family: sans-serif; font-size: 15px; line-height: 1.1; text-align: center; text-decoration: none; display: block; border-radius: 5px; padding: 10px 25px; color: {{ $color == 'primary' ? '#ffffff' : '#000000' }};" class="button">
                {{ $slot }}
            </a>
        </td>
    </tr>
</table>
<!-- End of the Button Component -->
