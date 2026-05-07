<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Server Error</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-yellow-50 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-2xl p-8 max-w-xl w-full text-center">

        <!-- Heading -->
        <h1 class="text-3xl font-bold text-green-700">
            Oops! An Error Occurred
        </h1>

        <!-- Image -->
        <div class="flex justify-center py-5">
            <img src="{{ asset('images/server-error.png') }}" alt="Server Error"
                class="max-w-[350px] w-full h-auto rounded-lg ">
        </div>

        <!-- Main Message -->
        <p class="text-gray-700 text-lg leading-relaxed mb-3">
            We apologize for the inconvenience. <br>It seems something went wrong on our end.
        </p>

        <div class="my-5 border-t border-gray-200 w-2/3 mx-auto"></div>

        <!-- Follow-up Message -->
        <p class="text-gray-600 text-sm leading-relaxed">
            Please let us know what you were doing when it happened. <br>
            Our team will look into it as soon as possible.
            <!-- Contact Info -->
        <div class="flex items-center justify-center gap-3 mb-8">
            <i class="fa-solid fa-envelope text-gray-600"></i>
            <span class="text-gray-600 text-sm leading-relaxed">rmscapstone26@gmail.com</span>
        </div>
        </p>


        <!-- Back to Home Button -->
        <x-button href="{{ route('guest.homepage') }}">
            Back to Home
        </x-button>

    </div>

</body>

</html>
