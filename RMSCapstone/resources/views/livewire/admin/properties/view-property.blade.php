<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Property') }}
        </h2>
    </x-slot>

    <div class="py-6 ">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-lg border shadow-md p-6">

            <!-- Back Button -->
            <div class="flex justify-end mb-4">
                <button onclick="history.back()"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Property Name -->
            <h2 class="mb-4 text-2xl md:text-3xl font-bold leading-tight text-gray-800 text-center">
                House: {{ $house->name_number }}
            </h2>

            <!-- House Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- House Image -->
                <div class="mb-4">
                    <img src="{{ asset($house->image ? 'storage/' . $house->image : 'images/rms-default.png') }}"
                        class="w-full h-64 object-cover rounded-lg shadow-md">
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
                        <li><strong>Capacity:</strong> {{ $house->capacity }}</li>
                        <li><strong>Max Adults:</strong> {{ $house->max_adults }}</li>
                        <li><strong>Max Kids:</strong> {{ $house->max_kids }}</li>
                        <li><strong>Availability:</strong> {{ ucfirst($house->property_status) }}</li>
                        <li><strong>Monthly Rent:</strong> {{ $house->amount }}</li>
                    </ul>

                    <!-- Room Amenities -->
                    <h3 class="mt-3 text-lg font-semibold text-gray-900">Amenities</h3>
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
                        <p class="text-gray-500">No amenities selected for this room.</p>
                    @endif

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
            <div class="flex items-center justify-between space-x-4 pt-2">
                <!-- Edit -->
                <x-button type="button" icon="fas fa-pen-to-square"
                    class="!text-black inline-flex items-center !bg-gray-200 hover:!bg-gray-300 font-medium rounded-lg text-sm px-6 py-2.5"
                    wire:navigate href="{{ route('admin.edit-property', ['property' => $house->id]) }}">
                    Edit
                </x-button>

                <!-- Delete -->
                <x-button type="button" icon="fas fa-trash"
                    class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-6 py-2.5"
                    wire:click="confirmDelete({{ $house->id }})" wire:loading.attr="disabled">
                    Delete
                </x-button>
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
</div>
