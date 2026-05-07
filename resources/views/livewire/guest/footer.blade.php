<footer class="bg-green-800 text-white text-center py-3">
    <div class="container mx-auto flex flex-col items-center">
        <div class="flex space-x-3 mb-2">
            <a href="{{ $facebookLink }}" target="_blank" class="text-white hover:text-gray-200">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://www.tiktok.com/@canopyfarmph" target="_blank" class="text-white hover:text-gray-200">
                <i class="fab fa-tiktok"></i>
            </a>
            <a href="{{ $instagramLink }}" target="_blank" class="text-white hover:text-gray-200">
                <i class="fab fa-instagram"></i>
            </a>
        </div>
        <div class="text-sm">
            <p>Email: <span class="font-medium">{{ $email }}</span></p>
            <p>Phone: <span class="font-medium">{{ $contactNumber }}</span></p>
            <p>Address: {{ $address }}</p>
        </div>
        <p class="mt-2 text-xs">
            &copy; <span id="year"></span> {{ $companyName }}. All rights reserved.
        </p>

        <script>
            document.getElementById("year").textContent = new Date().getFullYear();
        </script>
    </div>
</footer>
