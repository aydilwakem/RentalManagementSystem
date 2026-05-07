<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Under Maintenance</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-yellow-50 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-2xl p-8 max-w-xl w-full text-center">
        <!-- Heading -->
        <h1 class="text-3xl font-bold text-green-700">
            Hang tight! We’re tidying things up
        </h1>

        <!-- Image -->
        <div class="flex justify-center">
            <img src="{{ asset('images/maintenance-image.png') }}" alt="Maintenance"
                class="max-w-[350px] w-full h-auto rounded-lg">
        </div>

        <!-- Message -->
        <p class="text-gray-700 mb-4 text-lg">
            We apologize for the inconvenience <br>
            Our system is getting a quick clean and refresh. We’ll be back online shortly to serve you better.
        </p>

        <!-- Reservation Links -->
        <p class="text-gray-600 font-medium mb-3">
            You can still make a reservation through:
        </p>

        <div class="flex justify-center gap-6">
            <!-- Facebook -->
            <a href="https://www.facebook.com/yourcompany" target="_blank"
                class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 hover:bg-blue-200 transition">
                <i class="fa-brands fa-facebook text-blue-600 text-xl"></i>
            </a>

            <!-- Instagram -->
            <a href="https://www.instagram.com/yourcompany/" target="_blank"
                class="flex items-center justify-center w-12 h-12 rounded-full bg-pink-100 hover:bg-pink-200 transition">
                <i class="fa-brands fa-instagram text-pink-500 text-xl"></i>
            </a>

            <!-- Airbnb -->
            <a href="https://www.airbnb.com/users/show/17945450" target="_blank"
                class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 hover:bg-red-200 transition">
                <i class="fa-brands fa-airbnb text-red-500 text-xl"></i>
            </a>
        </div>
    </div>

</body>

</html>
