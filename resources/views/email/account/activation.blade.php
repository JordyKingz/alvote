@component('mail::message')
# Hi {{ $name }},

Thanks for signing up for ALVote.nl. 

Before loggin into the app you'll need to activate your account.

Follow the link: 
<a href="{{ $url }}">{{ $url }}</a>

If you have any questions we will be happy to help you. You can contact us on info@alvote.nl.

Regards,<br>
Team Alvote
@endcomponent