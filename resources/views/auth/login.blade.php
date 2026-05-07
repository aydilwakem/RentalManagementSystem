<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="icon" type="image/png" href="{{ asset('images/canopy-logo.png') }}">

</head>
<x-auth-layout>

    <body class="relative min-h-screen bg-green-800 text-black overflow-hidden">

        <!-- Image Background -->
        <div class="absolute inset-0 z-0">
            <div class="w-full h-full bg-cover bg-center filter blur-sm brightness-95"
                style="background-image: url('{{ asset('images/canopy-login2.png') }}');">
            </div>
        </div>

        <!-- Form -->
        <div class="relative z-10 p-4 flex items-center justify-center min-h-screen">
            <div class="flex flex-col items-center p-8 bg-white bg-opacity-90 rounded-3xl shadow-xl w-full max-w-sm">

                <!-- Logo -->
                <div class="relative -mt-20">
                    <div class="rounded-full bg-white p-2 shadow-md">
                        <img src="{{ asset('images/canopy-logo.png') }}" class="h-24 w-24" />
                    </div>
                </div>

                <p class="subheader text-2xl lg:text-4xl font-bold !text-green-700 text-center mt-3">
                    Welcome back!
                </p>

                <x-validation-errors class="mb-4" />

                @session('status')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ $value }}
                </div>
                @endsession

                @php
                $maxAttempts = 3;
                $throttleKey = Str::transliterate(
                strtolower(old(\Laravel\Fortify\Fortify::username())) . '|' . request()->ip()
                );
                $attempts = RateLimiter::attempts($throttleKey);
                $attemptsLeft = max(0, $maxAttempts - $attempts);
                $secondsLeft = RateLimiter::availableIn($throttleKey);
                $minutesLeft = $secondsLeft > 0 ? ceil($secondsLeft / 60) : 0;
                @endphp

                @if ($errors->has('email'))
                <div class="mb-4 font-medium text-sm text-red-600">
                    {{-- {{ $errors->first('email') }} --}}
                    @if ($attemptsLeft > 0)
                    <span>
                        {{ $attemptsLeft }} out of {{ $maxAttempts }} attempts left.
                    </span>
                    @else
                    <span>No more attempts left. Try again in {{ $minutesLeft }} minute{{ $minutesLeft == 1 ? '' : 's'
                        }}.</span>
                    @endif
                </div>
                @endif

                <div class="w-full flex flex-col items-center justify-center">

                    <form method="POST" action="{{ route('login') }}" class="w-full space-y-6 mt-2">
                        @csrf

                        <!-- Email -->
                        <div class="relative">
                            <input id="email"
                                class="block w-full py-3 px-12 rounded-full border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none bg-gray-100 placeholder-gray-500"
                                type="email" name="email" :value="old('email')" required autofocus
                                autocomplete="username" placeholder="Email" />

                            <!-- icon -->
                            <div class="absolute inset-y-0 left-0 flex items-center pl-5">
                                <i class="fa-solid fa-envelope text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="relative" x-data="{ show: false }">
                            <input id="password" :type="show ? 'text' : 'password'"
                                class="block w-full py-3 px-12 rounded-full border border-gray-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none bg-gray-100 placeholder-gray-500"
                                name="password" required autocomplete="current-password" placeholder="Password" />

                            <!-- icon -->
                            <div class="absolute inset-y-0 left-0 flex items-center pl-5">
                                <i class="fa-solid fa-lock text-gray-400"></i>
                            </div>

                            <!-- Toggle button -->
                            <div class="absolute inset-y-0 right-0 flex items-center pr-5">
                                <i id="togglePassword" class="fa-solid fa-eye text-gray-400 cursor-pointer"></i>
                            </div>

                        </div>


                        <!-- Remember & Forgot -->
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center">
                                <input id="remember_me" name="remember" type="checkbox"
                                    class="rounded text-green-500 focus:ring-green-500 border-gray-300" />
                                <label for="remember_me" class="ml-2 text-gray-600">Remember me</label>
                            </div>
                            @if (Route::has('password.request'))
                            <a class="text-gray-600 hover:text-green-700 hover:underline focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none"
                                href="{{ route('password.request') }}">
                                Forgot Password?
                            </a>
                            @endif
                        </div>

                        <!-- Login Button -->
                        <button type="submit"
                            class="w-full py-3 px-6 rounded-full text-sm tracking-widest text-white font-bold bg-green-800 hover:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            LOGIN
                        </button>

                        <div class="mt-4 text-sm text-gray-600 flex justify-center space-x-1">
                            <p>Don't have an account?</p>
                            <a href="{{ route('register') }}"
                                class="text-primary font-semibold hover:underline hover:text-green-700">Sign
                                Up</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
        @endif

    </body>
    <script>
        const toggle = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        toggle.addEventListener('click', function() {
            const isPassword = password.type === 'password';
            password.type = isPassword ? 'text' : 'password';
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</x-auth-layout>

</html>