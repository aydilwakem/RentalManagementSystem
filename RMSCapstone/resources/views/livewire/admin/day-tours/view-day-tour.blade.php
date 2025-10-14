<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('View Day Tour') }}
        </h2>
        <x-breadcrumbs :items="[
            ['label' => 'Day Tours', 'url' => route('admin.day-tours')],
            ['label' => 'View Day Tour', 'url' => route('admin.view-day-tour', ['dayTour' => $dayTour->id])],
        ]" />
    </x-slot>

    <div class="py-3 mb-4">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600">

            <div class="relative flex items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">
                    Day Tour: {{ $dayTour->name }}
                </h2>
                <button onclick="window.location.href='{{ route('admin.day-tours') }}'"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Day Tour Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="grid grid-cols-1 gap-2">
                    <!-- Main Image -->
                    @if ($dayTour->main_image)
                        <div class="w-full">
                            <img src="{{ $dayTour->main_image_url }}" 
                                class="w-full h-72 object-cover rounded border cursor-pointer" 
                                alt="Main Day Tour Image"
                                onclick="openModal('{{ $dayTour->main_image_url }}')">
                        </div>
                    @else
                        <div class="w-full">
                            <img src="{{ asset('images/daytour-default.png') }}" 
                                class="w-full h-72 object-cover rounded border cursor-pointer" 
                                alt="Default Day Tour Image"
                                onclick="openModal('{{ asset('images/daytour-default.png') }}')">
                        </div>
                    @endif

                    <!-- Additional Images -->
                    @if (count($dayTour->images) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            @foreach (array_slice($dayTour->images, 0, 3) as $img)
                                <img src="{{ asset('storage/' . $img) }}" 
                                    class="w-full h-44 object-cover rounded border cursor-pointer" 
                                    alt="Day Tour Image"
                                    onclick="openModal('{{ asset('storage/' . $img) }}')">
                            @endforeach
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

                <!-- Day Tour Details -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-50 mb-4">Tour Details</h3>
                    <ul class="list-disc pl-5 text-gray-600 mb-3 dark:text-gray-300 space-y-2">
                        <li><strong>Description:</strong> {{ $dayTour->description }}</li>
                        <li><strong>Duration:</strong> {{ $dayTour->duration_hours }} hours</li>
                        <li><strong>Start Time:</strong> {{ $dayTour->start_time->format('h:i A') }}</li>
                        <li><strong>End Time:</strong> {{ $dayTour->end_time->format('h:i A') }}</li>
                        <li><strong>Maximum Guests:</strong> {{ $dayTour->max_guests }}</li>
                        <li><strong>Base Price:</strong> ₱{{ number_format($dayTour->base_price, 2) }}</li>
                        <li>
                            <strong>Status:</strong> 
                            @if ($dayTour->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active <i class="fa-solid fa-check pl-1"></i>
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </li>
                        <li><strong>Created:</strong> {{ $dayTour->created_at->format('M d, Y h:i A') }}</li>
                        <li><strong>Last Updated:</strong> {{ $dayTour->updated_at->format('M d, Y h:i A') }}</li>
                    </ul>

                    <!-- Inclusions -->
                    @if ($dayTour->inclusions)
                        <h4 class="text-md font-semibold text-gray-900 dark:text-gray-50 mt-4 mb-2">Inclusions</h4>
                        <ul class="list-disc pl-5 text-gray-600 dark:text-gray-300 space-y-1">
                            @foreach ($dayTour->formatted_inclusions as $inclusion)
                                <li>{{ $inclusion }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <!-- Exclusions -->
                    @if ($dayTour->exclusions)
                        <h4 class="text-md font-semibold text-gray-900 dark:text-gray-50 mt-4 mb-2">Exclusions</h4>
                        <ul class="list-disc pl-5 text-gray-600 dark:text-gray-300 space-y-1">
                            @foreach ($dayTour->formatted_exclusions as $exclusion)
                                <li>{{ $exclusion }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <!-- Terms & Conditions -->
            @if ($dayTour->terms_conditions)
                <div class="mt-6 p-4 bg-gray-50 rounded-lg dark:bg-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-50 mb-3">Terms & Conditions</h3>
                    <p class="text-gray-600 dark:text-gray-300 whitespace-pre-line">{{ $dayTour->terms_conditions }}</p>
                </div>
            @endif

            <!-- Associated Rates -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-50 mb-4">Associated Rates</h3>
                @if ($dayTour->rates->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead class="text-xs text-gray-700 bg-gray-200 dark:bg-gray-800 dark:text-white">
                                <tr>
                                    <th class="px-4 py-2">Rate Name</th>
                                    <th class="px-4 py-2">Type</th>
                                    <th class="px-4 py-2">Day Type</th>
                                    <th class="px-4 py-2">Adult Rate</th>
                                    <th class="px-4 py-2">Kid Rate</th>
                                    <th class="px-4 py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody class="dark:bg-gray-700">
                                @foreach ($dayTour->rates as $rate)
                                    <tr class="border-b dark:border-gray-600">
                                        <td class="px-4 py-2 dark:text-white">{{ $rate->rate_name }}</td>
                                        <td class="px-4 py-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs 
                                                {{ $rate->rate_type === 'with_room' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $rate->rate_type_label }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs 
                                                {{ $rate->day_type === 'holiday' ? 'bg-red-100 text-red-800' : 
                                                   ($rate->day_type === 'weekend' ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800') }}">
                                                {{ $rate->day_type_label }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 font-semibold text-green-600">₱{{ number_format($rate->adult_rate, 2) }}</td>
                                        <td class="px-4 py-2 font-semibold text-green-600">₱{{ number_format($rate->kid_rate, 2) }}</td>
                                        <td class="px-4 py-2">
                                            @if ($rate->is_active)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-red-100 text-red-800">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-300 text-center py-4">No rates associated with this day tour yet.</p>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between space-x-4 mt-6">
                <x-ghost-button type="button" icon="fas fa-pen-to-square" wire:navigate
                    href="{{ route('admin.edit-day-tour', ['dayTour' => $dayTour->id]) }}">
                    Edit
                </x-ghost-button>

                <x-danger-button type="button" icon="fas fa-trash" wire:click="confirmDelete({{ $dayTour->id }})">
                    Delete
                </x-danger-button>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
            <x-slot name="title">
                {{ __('Delete Day Tour') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this day tour?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deleteDayTour" wire:loading.attr="disabled">
                    {{ __('Delete Day Tour') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>

        {{-- Cannot Delete Modal --}}
        <x-dialog-modal wire:model="cannotDeleteItem" type="ghost">
            <x-slot name="title">
                {{ __('Unable to Delete') }}
            </x-slot>

            <x-slot name="content">
                {{ __('This day tour has associated rates and cannot be deleted.') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                    {{ __('OK') }}
                </x-secondary-button>
            </x-slot>
        </x-dialog-modal>
    </div>
</div>

<!-- Image Modal Script -->
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