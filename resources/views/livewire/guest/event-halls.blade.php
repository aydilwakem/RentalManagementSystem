<div class="max-w-7xl mx-auto px-6 py-7 mb-8">
    <h1 class="text-green-700 font-bold tracking-wide uppercase mb-4 text-center text-3xl">
        Spaces for lasting memories
        <span class="block mx-auto mt-2 w-16 h-1 bg-green-600 rounded-full"></span>
    </h1>

    <!-- Inquire Text -->
    <p class="text-lg text-gray-700 text-center mb-7">
        Inquire by sending an email to
        <span class="text-green-600">events.canopyfarmindang@gmail.com</span>
        or call this number <span class="text-green-600">{{ $contactNumber }}</span> for more information about event
        reservations
    </p>

    <!-- Event Hall Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($eventHalls as $hall)
            <div wire:key="hall-{{ $hall->id }}"
                class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 flex flex-col border border-gray-100">

                <!-- Image -->
                <div x-data="{
                    active: 0,
                    images: {{ json_encode($hall->images ?? []) }},
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

                    <!-- Prev Button -->
                    <button x-show="hover" @click.stop="prevImage()"
                        class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 rounded-full p-2 shadow hover:bg-opacity-100 transition">
                        <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Next Button -->
                    <button x-show="hover" @click.stop="nextImage()"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 rounded-full p-2 shadow hover:bg-opacity-100 transition">
                        <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Dots (for navigation) -->
                    <div x-cloak
                        class="absolute bottom-2 left-1/2 transform -translate-x-1/2 flex gap-1 transition-opacity duration-300"
                        :class="hover && images.length > 1 ? 'opacity-100' : 'opacity-0'">
                        <template x-for="(image, index) in images" :key="index">
                            <button @click.stop="active = index"
                                :class="active === index ? 'bg-green-400 scale-110' : 'bg-white'"
                                class="w-2.5 h-2.5 rounded-full border border-white/50 transition-all duration-300"></button>
                        </template>
                    </div>
                </div>

                <div class="flex flex-col justify-between p-6">
                    <!-- Hall Name -->
                    <h5 class="text-2xl font-semibold text-gray-900 ">{{ $hall->name_number }}</h5>

                    <!-- Event Description -->
                    <p class="text-base text-gray-700 mb-2 text-justify">
                        @if (empty($hall->description))
                            <span class="text-gray-600">Try this Hall only at Canopy Farm!</span>
                        @elseif ($expandedHall === $hall->id)
                            {{ $hall->description }}
                            <a href="#" wire:click.prevent="toggleHallDescription({{ $hall->id }})"
                                class="text-gray-600 hover:underline ml-1 ">Show
                                less</a>
                        @else
                            {{ Str::limit($hall->description, 120, '...') }}
                            @if (Str::length($hall->description) > 100)
                                <a href="#" wire:click.prevent="toggleHallDescription({{ $hall->id }})"
                                    class="text-gray-600 hover:underline ml-1 ">Show
                                    more</a>
                            @endif
                        @endif
                    </p>

                    <!-- Maximum Capacity -->
                    <p class="text-sm text-gray-700  flex items-center">
                        <i class="fas fa-users mr-2"></i>Maximum Capacity: {{ $hall->capacity }} guests
                    </p>

                    <!-- Rate -->
                    <div>
                        <p class="text-lg font-semibold text-green-600">
                            ₱{{ number_format($hall->amount, 0, '.', ',') }}
                            <span class="text-sm font-medium text-gray-500">/ first 4 hours</span>
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            Extra ₱{{ number_format($hall->extra_charge_per_hour, 0, '.', ',') }} per additional hour
                        </p>
                    </div>

                    <!-- Request a Quote Button -->
                    <div class="mt-4">
                        <x-button class="transition duration-300 transform hover:scale-105"
                            href="{{ route('guest.request-a-quote') }}">
                            Request a Quote
                        </x-button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Image Modal Popup View -->
    <div id="imageModal" class="fixed z-50 inset-0 overflow-y-auto bg-black bg-opacity-80 hidden">
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

    <!-- Modal Functions -->
    <script>
        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImg');
            modalImg.src = imageSrc;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    </script>
</div>
