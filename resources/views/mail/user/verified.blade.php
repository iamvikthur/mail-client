<x-mail::message>
# Congratulations {{ $firstname }}

Your account has been verified.

Enjoy!

<x-mail::button :url="$loginUrl">
Continue Here
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
