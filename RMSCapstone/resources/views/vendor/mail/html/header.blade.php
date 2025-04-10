@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
            <img src="RMSCapstone\public\images\canopy-logo.png" class="logo" alt="Canopy Farm Logo">
            @else
            {{ $slot }}
            @endif
        </a>
    </td>
</tr>