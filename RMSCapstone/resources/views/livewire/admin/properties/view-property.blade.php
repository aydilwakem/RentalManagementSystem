<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Property') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-lg border shadow-md p-6">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center">House: {{ $house->name_number }}</h2>

                <!-- Back Button -->
                <button onclick="history.back()"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- House Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- House Image -->
                <div class="grid grid-cols-1 gap-2">
                    @if (isset($house->images) && count($house->images) > 0)
                        <div class="w-full">
                            <img src="{{ asset('storage/' . $house->images[0]) }}"
                                class="w-full h-72 object-cover rounded border cursor-pointer" alt="Main house Image"
                                onclick="openModal('{{ asset('storage/' . $house->images[0]) }}')">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            @foreach (array_slice($house->images, 1) as $img)
                                <img src="{{ asset('storage/' . $img) }}"
                                    class="w-full h-44 object-cover rounded border cursor-pointer" alt="house Image"
                                    onclick="openModal('{{ asset('storage/' . $img) }}')">
                            @endforeach
                        </div>
                    @else
                        <div class="w-full">
                            <img src="{{ asset('images/rms-default.png') }}"
                                class="w-full h-72 object-cover rounded border cursor-pointer" alt="Default Image"
                                onclick="openModal('{{ asset('images/rms-default.png') }}')">
                        </div>
                    @endif
                </div>
                <!-- Image Popup View -->
                <div id="imageModal" class="fixed z-50 inset-0 overflow-y-auto bg-black bg-opacity-80 hidden">
                    <div class="flex items-center justify-center min-h-screen">
                        <div class=" relative modal-content">
                            <img id="modalImg" src="" class="max-w-full max-h-[80vh] rounded-md">
                            <button onclick="closeModal()"
                                class="absolute top-2 right-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                                <span class="leading-none translate-y-[-3px]">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{--
                <!-- Description -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Description</h3>
                        @if (!empty($house->description))
                        <p class="text-gray-600 leading-relaxed">{{ $house->description }}</p>
                        @else
                        <p class="text-gray-500 italic">No description provided.</p>
                        @endif
                    </div>
                </div> --}}


                <!-- House Details -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900">House Details</h3>
                    <ul class="list-disc pl-5 text-gray-600">
                        {{-- <li><strong>Capacity:</strong> {{ $house->capacity }}</li>
                        <li><strong>Max Adults:</strong> {{ $house->max_adults }}</li>
                        <li><strong>Max Kids:</strong> {{ $house->max_kids }}</li> --}}
                        <li><strong>Availability:</strong> {{ ucfirst($house->property_status) }}</li>
                        <li><strong>Monthly Rent:</strong> {{ $house->amount }}</li>
                    </ul>

                    <!-- house Amenities -->
                    {{-- <h3 class="mt-3 text-lg font-semibold text-gray-900">Amenities</h3>
                    @if ($house->features->isNotEmpty())
                        <div class="flex flex-wrap gap-2">
                            @foreach ($house->features as $feature)
                                <span
                                    class="inline-flex items-center rounded-full bg-gray-200 px-3 py-1 text-sm font-semibold text-gray-700">
                                    {{ $feature->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No amenities selected for this house.</p>
                    @endif --}}

                    <!-- Address -->
                    <div>
                        <h3 class="mt-3 text-lg font-semibold text-gray-900">Address</h3>
                        <p class="text-gray-600">
                            {{ $house->house_number }}, {{ $house->street }},
                            {{ $house->barangay }}, {{ $house->city_municipality }}, {{ $house->region }},
                            {{ $house->postal_code }}, {{ $house->country }}
                        </p>
                    </div>
                </div>


            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between space-x-4 pt-2 mt-4">
                <!-- Edit -->
                <x-ghost-button type="button" icon="fas fa-pen-to-square"
                    wire:navigate href="{{ route('admin.edit-property', ['property' => $house->id]) }}">
                    Edit
                </x-ghost-button>

                <!-- Delete -->
                <x-danger-button type="button" icon="fas fa-trash"
                    wire:click="confirmDelete({{ $house->id }})" wire:loading.attr="disabled">
                    Delete
                </x-danger-button>
            </div>


            <!-- Delete Confirmation Modal -->
            <x-dialog-modal wire:model.live="confirmItemDelete">
                <x-slot name="title">
                    {{ __('Delete Property') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to delete this item?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3" wire:click="deleteHouse({{ $house->id }})"
                        wire:loading.attr="disabled">
                        {{ __('Delete Property') }}
                    </x-danger-button>
                </x-slot>
            </x-dialog-modal>

            {{-- Cannot Delete Modal --}}
            <x-dialog-modal wire:model="cannotDeleteItem">
                <x-slot name="title">
                    {{ __('Unable to Delete') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('This house is currently in use and cannot be deleted.') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                        {{ __('OK') }}
                    </x-secondary-button>
                </x-slot>
            </x-dialog-modal>
        </div>
    </div>
    <script>
        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImg');
            modalImg.src = imageSrc;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scroll
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Restore background scroll
        }
    </script>
</div>
