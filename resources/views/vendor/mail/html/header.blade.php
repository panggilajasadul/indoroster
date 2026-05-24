@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel' || trim($slot) === config('app.name'))
    @if(isset($message) && method_exists($message, 'embed'))
        <img src="{{ $message->embed(public_path('assets/logo_indoroster-text.png')) }}" class="logo" alt="{{ config('app.name') }}" style="max-height: 50px;">
    @else
        <img src="{{ asset('assets/logo_indoroster-text.png') }}" class="logo" alt="{{ config('app.name') }}" style="max-height: 50px;">
    @endif
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
