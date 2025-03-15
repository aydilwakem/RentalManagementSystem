<div class="min-h-[550px] container mx-auto p-8 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Activity') }}
        </h2>
    </x-slot>

    <div class="py-3 px-8 mx-auto max-w-2xl border rounded-lg bg-white shadow-md mt-4 mb-4">
        <h2 class="mb-4 text-xl font-semibold leading-none text-gray-900 md:text-2xl text-center">
            {{ $activity->name }}
        </h2>

        <!-- Activity Image -->
        <div class="mb-4">
            <img src="{{ asset('storage/' . $activity->image) }}" alt="{{ $activity->name }}"
                class="w-full h-64 object-cover rounded-lg shadow-md">
        </div>

        <!-- Description -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Description</h3>
            <p class="font-light text-gray-500">
                {{ $activity->description }}
            </p>
        </div>

        <!-- Amount -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Amount</h3>
            <p class="font-light text-gray-500">
                {{ number_format($activity->amount, 2) }}
            </p>
        </div>

        <!-- Inclusions -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Inclusions</h3>
            <p class="font-light text-gray-500">
                {{ $activity->inclusions }}
            </p>
        </div>

        <!-- Timestamps -->
        {{-- <div class="mb-4 text-sm text-gray-500">
            <p>Created At: {{ $activity->created_at->format('F j, Y g:i A') }}</p>
            <p>Updated At: {{ $activity->updated_at->format('F j, Y g:i A') }}</p>
        </div> --}}

        <!-- Action Buttons -->
        <div class="flex items-center space-x-4">
            {{-- Edit Button --}}
            <a href="{{ route('admin.edit-activity', ['activity' => $activity->id]) }}"
                class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">

                <svg aria-hidden="true" class="mr-1 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                    <path fill-rule="evenodd"
                        d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                        clip-rule="evenodd"></path>
                </svg>
                Edit
            </a>

            {{-- Delete Button --}}
            <x-button type="button"
                class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                wire:click="deleteActivity({{ $activity->id }})">

                <svg aria-hidden="true" class="w-5 h-5 mr-1.5 -ml-1" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                        clip-rule="evenodd"></path>
                </svg>
                Delete
            </x-button>
        </div>
    </div>

</div>
