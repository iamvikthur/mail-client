<x-mail::message>
# Hello {{ $firstname }}

Use the code below to complete your action

<h1>{{ $token }} </h1>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
