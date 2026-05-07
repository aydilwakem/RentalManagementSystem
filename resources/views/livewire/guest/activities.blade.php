<div>
    <div class="max-w-7xl mx-auto px-6 py-7 mb-8">
        <div class="text-center">
            <h1 class="text-green-700 font-bold tracking-wide uppercase mb-4 text-center text-3xl">
                What to do at {{ $companyName }}?
                <span class="block mx-auto mt-2 w-16 h-1 bg-green-600 rounded-full"></span>
            </h1>
            {{-- <h1 class="text-3xl font-bold text-green-700 mb-3 text-center">What to do at Canopy Farm?</h1> --}}
            <p class="text-lg text-gray-700 text-center mx-auto mb-8 w-full md:w-2/3">
                Looking for a place where relaxation meets adventure? Here at {{ $companyName }}, every corner is made
                for unforgettable experiences—perfect for families, friends, and nature lovers alike!
            </p>

        </div>

        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($activities as $activity)
                <div
                    class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-on-scroll opacity-0 translate-y-10 flex flex-col border border-gray-100">

                    <!-- Image -->
                    <div x-data="{
                        active: 0,
                        images: {{ json_encode($activity->images ?? []) }},
                        hover: false,
                        nextImage() { this.active = (this.active + 1) % this.images.length },
                        prevImage() { this.active = (this.active - 1 + this.images.length) % this.images.length },
                    }" @mouseenter="hover = true" @mouseleave="hover = false"
                        class="relative w-full h-[260px] overflow-hidden">

                        <!-- Image Loop -->
                        <template x-for="(image,index) in images" :key="index">
                            <img x-show="active===index" :src="'/storage/' + image"
                                class="absolute inset-0 w-full h-full object-cover transition duration-700 "
                                x-transition.opacity />
                        </template>

                        <!-- Default Image -->
                        <img x-show="images.length===0" src="{{ asset('images/rms-default.png') }}"
                            class="absolute inset-0 w-full h-full object-cover">

                        <!-- Overlay Gradient -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>

                        <!-- Navigation arrows -->
                        <button x-show="hover && images.length > 1" @click="prevImage()"
                            class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white p-2 rounded-full shadow">
                            <i class="fa-solid fa-chevron-left text-gray-700 text-xs w-5 h-5"></i>
                        </button>

                        <button x-show="hover && images.length > 1" @click="nextImage()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white p-2 rounded-full shadow">
                            <i class="fa-solid fa-chevron-right text-gray-700 text-xs w-5 h-5"></i>
                        </button>

                        <!-- Dots -->
                        <div x-cloak
                            class="absolute bottom-3 left-1/2 -translate-x-1/2 flex space-x-1.5 transition-opacity duration-300"
                            :class="hover && images.length > 1 ? 'opacity-100' : 'opacity-0'">
                            <template x-for="(img, i) in images" :key="i">
                                <div @click="active = i" :class="active === i ? 'bg-green-500 scale-110' : 'bg-white'"
                                    class="w-2.5 h-2.5 rounded-full border border-white/50 transition-all duration-300 cursor-pointer">
                                </div>
                            </template>
                        </div>


                    </div>

                    <!-- Details -->
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            {{ $activity->name }}
                        </h3>

                        <p class="text-gray-600 text-sm mb-4 leading-relaxed line-clamp-4">
                            {{ $activity->description ?: 'Try this activity only at Canopy Farm!' }}
                        </p>

                        <div class="mt-auto text-green-600 font-extrabold text-lg tracking-wide">
                            @if ($activity->amount == 0)
                                FREE
                            @else
                                ₱{{ number_format($activity->amount, 2) }}
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
    {{-- scroll animation --}}
    <script>
        function initActivityAnimations() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.remove("opacity-0", "translate-y-10");
                        entry.target.classList.add("opacity-100", "translate-y-0");
                        entry.target.style.transition = "all 800ms cubic-bezier(0.25,0.1,0.25,1)";
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.15
            });

            document.querySelectorAll(".animate-on-scroll").forEach((el, i) => {
                el.style.transitionDelay = `${i * 150}ms`;
                observer.observe(el);
            });
        }

        document.addEventListener("DOMContentLoaded", initActivityAnimations);

        document.addEventListener("livewire:navigated", initActivityAnimations);
    </script>

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
