@component('mail::message')
# Welcome to {{ config('app.name') }}

Hi {{ $user->name }},

An account has been created for you. Click the button below to set up your passkey and get started.

@component('mail::button', ['url' => $enrollmentUrl])
Set up your passkey
@endcomponent

This link expires in 24 hours. If you did not expect this invitation, you can ignore this email.

Thanks,
{{ config('app.name') }}
@endcomponent
