<div>
    <div class="max-w-7xl mx-auto px-6 py-7 mb-8">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-green-700 mb-3 text-center">Our Activities</h1>
            <p class="text-lg text-gray-700 text-center mb-3">
                These activities are <b>add-ons</b> to your bookings, enhancing your experience during your stay at
                {{ $companyName }}.<br> Book a room now and enjoy a variety of exciting experiences!
            </p>
            <x-button class="mb-8" href="{{ route('guest.reservation-form') }}">
                Book Room Now
            </x-button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if (!empty($activities) && $activities->count())
                <!-- Activity Card -->
                @foreach ($activities as $activity)
                    <div
                        class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition flex flex-col">

                        <!-- Image -->
                        <div x-data="{
                            active: 0,
                            images: {{ json_encode($activity->images ?? []) }},
                            hover: false,
                            nextImage() {
                                this.active = (this.active + 1) % this.images.length;
                            },
                            prevImage() {
                                this.active = (this.active - 1 + this.images.length) % this.images.length;
                            }
                        }" class="relative w-full h-[250px] overflow-hidden rounded-t-lg"
                            @mouseenter="hover = true" @mouseleave="hover = false">

                            <!-- Images Section -->
                            <div>
                                <template x-for="(image, index) in images" :key="index">
                                    <img x-show="active === index" :src="'/storage/' + image"
                                        class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300"
                                        x-transition:enter="transition ease-out duration-500"
                                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                        :alt="'House image ' + (index + 1)" @click="openModal('/storage/' + image)" />
                                </template>

                                <!-- Default Image if no images are available -->
                                <img x-show="images.length === 0" src="{{ asset('images/rms-default.png') }}"
                                    class="absolute inset-0 w-full h-full object-cover" alt="Default Image" />
                            </div>

                            <!-- Image Modal Popup View -->
                            <div id="imageModal"
                                class="fixed z-50 inset-0 overflow-y-auto bg-black bg-opacity-80 hidden">
                                <div class="flex items-center justify-center min-h-screen">
                                    <div class="relative modal-content">
                                        <img id="modalImg" src="" class="max-w-full max-h-[80vh] rounded-md">
                                        <button onclick="closeModal()"
                                            class="absolute top-2 right-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                                            <span class="leading-none translate-y-[-3px]">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Prev Button (Visible only on hover) -->
                            <button x-show="hover" @click="prevImage()"
                                class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 rounded-full p-2 shadow hover:bg-opacity-100 transition">
                                <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>

                            <!-- Next Button (Visible only on hover) -->
                            <button x-show="hover" @click="nextImage()"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 rounded-full p-2 shadow hover:bg-opacity-100 transition">
                                <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>

                            <!-- Dots (for navigation) -->
                            <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 flex gap-1">
                                <template x-for="(image, index) in images" :key="index">
                                    <button @click="active = index"
                                        :class="{
                                            'bg-white': active !== index,
                                            'bg-green-300': active === index
                                        }"
                                        class="w-2.5 h-2.5 rounded-full transition-all duration-300"></button>
                                </template>
                            </div>
                        </div>

                        <!-- Activity Details -->
                        <div class="flex flex-col justify-between px-6 py-3">
                            <h2 class="text-xl font-semibold text-gray-800"> {{ $activity->name }}</h2>

                            <p class="text-gray-600 text-sm mb-2 text-justify">
                                @if (!empty($activity->description))
                                    {{ $activity->description }}
                                @else
                                    Try this activity only at Canopy Farm!
                                @endif
                            </p>

                            <div class="text-right mt-auto">
                                <span class="text-green-600 font-bold text-lg">
                                    @if ($activity->amount == 0)
                                        <span class="text-green-600 font-semibold">FREE</span>
                                    @else
                                        ₱{{ number_format($activity->amount, 2) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-center text-gray-500 py-10">No activities available at the moment.</p>
            @endif

        </div>
    </div>
    <!-- Popup Function -->
    <script>
        // Function to open modal with the clicked image
        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImg');
            modalImg.src = imageSrc;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scroll
        }

        // Function to close the modal
        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Restore background scroll
        }
    </script>
</div>
