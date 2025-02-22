<x-mail::message>
# Hello {{ $firstname }}

We are pleased to have you on Lmail, please verify your email address with the token

<h1>{{ $token }} </h1>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
