<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify Email</title>
</head>
<x-auth-layout>
    <body class="bg-green-800 text-black lg:justify-center flex-col flex items-center justify-center bg-opacity-90 min-h-screen">


        <x-authentication-card>
            <x-slot name="logo">
                <img src="{{ asset('images/canopy-logo.png') }}" class="h-28 w-28 object-cover rounded-full">
            </x-slot>

            <div class="mb-6 text-md text-gray-600">
                {{ __('To continue, please verify your email address by clicking the link we’ve just sent to your inbox. This helps us confirm it’s really you. If you didn’t receive the email, don’t worry — we’re happy to send you another one.') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ __('A new verification link has been sent to the email address you provided in your profile settings.') }}
                </div>
            @endif

            <div class="mt-4 flex items-center flex-col">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf

                    <div>
                        <x-button type="submit">
                            {{ __('Resend Verification Email') }}
                        </x-button>
                    </div>
                </form>

                <div class="justify-between mt-3">
                    <a href="{{ route('profile.show') }}"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        {{ __('Edit Profile') }}</a>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf

                        <button type="submit"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 ms-2">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </x-authentication-card>
    </body>
</x-auth-layout>

</html>
