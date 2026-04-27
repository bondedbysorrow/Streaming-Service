@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://drive.google.com/file/d/1LLzvYkVR7_l0Bo-vXaSQ4fUtUcj0fyaY/view?usp=sharing" class="logo" alt="Laravel Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
