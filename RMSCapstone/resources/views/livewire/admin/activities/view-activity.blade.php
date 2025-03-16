<div class="min-h-[550px] container mx-auto p-8 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Activity') }}
        </h2>
    </x-slot>

    <div class="py-6 px-10 mx-auto max-w-3xl border rounded-xl bg-white shadow-lg mt-6 mb-6 space-y-6">
        <!-- Activity Name -->
        <h2 class="text-2xl md:text-3xl font-bold text-center text-gray-900">
            {{ $activity->name }}
        </h2>

        <!-- Activity Image -->
        <div>
            <img src="{{ asset('storage/' . $activity->image) }}" alt="{{ $activity->name }}"
                class="w-full h-72 object-cover rounded-xl shadow-md">
        </div>

        <!-- Description -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Description</h3>
            <p class="text-gray-600 leading-relaxed">
                {{ $activity->description }}
            </p>
        </div>

        <!-- Amount -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Amount</h3>
            <p class="text-gray-600">
                {{ number_format($activity->amount, 2) }}
            </p>
        </div>

        <!-- Inclusions -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Inclusions</h3>
            <p class="text-gray-600 leading-relaxed">
                {{ $activity->inclusions }}
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between space-x-4 pt-2">
            <!-- Edit -->
            <x-button type="button" icon="fas fa-pen-to-square"
                class="!text-black inline-flex items-center !bg-gray-200 hover:!bg-gray-300 font-medium rounded-lg text-sm px-6 py-2.5"
                wire:navigate href="{{ route('admin.edit-activity', ['activity' => $activity->id]) }}">
                Edit
            </x-button>

            <!-- Delete -->
            <x-button type="button" icon="fas fa-trash"
                class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-6 py-2.5"
                wire:click="deleteActivity({{ $activity->id }})">
                Delete
            </x-button>
        </div>
    </div>


</div>
