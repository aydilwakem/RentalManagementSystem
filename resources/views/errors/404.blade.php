<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Page Not Found</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-yellow-50 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-2xl p-8 max-w-xl w-full text-center">
        <!-- Heading -->
        <h1 class="text-4xl font-bold text-green-700">
            Page Not Found
        </h1>

        <!-- Image -->
        <div class="flex justify-center">
            <img src="{{ asset('images/page-not-found.png') }}" alt="Page Not Found"
                class="max-w-[350px] w-full h-auto rounded-lg">
        </div>

        <!-- Message -->
        <p class="text-gray-600 font-medium mb-3">
            The page you’re looking for doesn’t exist or may have been moved.
            Let's get you back on track
        </p>
        <x-button href="{{ route('guest.homepage') }}">
            Back to Home
        </x-button>

    </div>

</body>

</html>
