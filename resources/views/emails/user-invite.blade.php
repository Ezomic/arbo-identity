@component('mail::message')
# Welcome to {{ config('app.name') }}

Hi {{ $user->name }},

An account has been created for you. Click the button below to set your password and get started.

@component('mail::button', ['url' => $resetUrl])
Set your password
@endcomponent

This link expires in {{ config('auth.passwords.users.expire') }} minutes. If you did not expect this invitation, you can ignore this email.

Thanks,
{{ config('app.name') }}
@endcomponent
