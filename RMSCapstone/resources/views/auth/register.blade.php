<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
</head>

<body class="flex min-h-screen items-center justify-center bg-green-800 bg-opacity-90 p-8 lg:p-10">
    <x-guest-layout>
        <div
            class="flex flex-col lg:flex-row w-full max-w-4xl shadow-lg rounded-lg overflow-hidden my-6 lg:my-12 bg-white">

            <!-- Left Section - Image -->
            <!-- to flush image to corners / remove spacing: remove p-4 and rounded tags. di ako maka decide ano mas okay xD -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-4 rounded-3xl">
                <img src="{{ asset('images/pool-house1.jpg') }}" alt="Image"
                    class="w-full h-[300px] lg:h-full object-cover object-center rounded-xl">
            </div>

            <!-- Right Section -->
            <div class="w-full lg:w-1/2 p-6 lg:p-10 flex flex-col justify-center">
                <p class="subheader text-3xl lg:text-4xl font-bold !text-green-700 mb-2 text-center">Create an account
                </p>

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div class="flex flex-col lg:flex-row gap-2">
                        <div class="w-full">
                            <x-label for="name" value="{{ __('First Name') }}" />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                                required autofocus />
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ni-remove ko muna require kasi wala pa 'tong field na 'to sa users table -->
                        <div class="w-full">
                            <x-label for="lname" value="{{ __('Last Name') }}" />
                            <x-input id="lname" class="block mt-1 w-full" type="text" name="lname"
                                :value="old('lname')" />
                            @error('lname')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <x-label for="email" value="{{ __('Email') }}" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                            required />
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-label for="password" value="{{ __('Password') }}" />
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                        <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                            name="password_confirmation" required />
                        @error('password_confirmation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>



                    <div class="flex items-center justify-between mt-4">
                        <a class="text-sm text-gray-600 hover:underline" href="{{ route('admin.welcome') }}">
                            {{ __('Already registered?') }}
                        </a>
                        <x-button>Register</x-button>
                    </div>
                </form>

            </div>
        </div>
    </x-guest-layout>
</body>

</html>