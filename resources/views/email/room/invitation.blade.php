@component('mail::message')
# Hi,

You're invited to join a conference room. 

You can join your room to click the following link:
<a href="{{ $url }}">{{ $url }}</a>

Join your room with the following codes:
Room code: {{ $room_code }}
Personal code: {{ $personal_code }}

If you have any questions we will be happy to help you. You can contact us on info@alvote.nl.

Regards,<br>
Team Alvote
@endcomponent