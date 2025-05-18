<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Transaction') }}
        </h2>
    </x-slot>


    <div>
        {{-- ------------------------- Selected Activities (existing) --------------- --}}
        @if ($transaction->activities->isNotEmpty())
            <div class="overflow-x-auto bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="font-semibold text-lg text-gray-700 mb-3">Existing Activities</h3>
                <table class="table-auto w-full border border-gray-300 text-sm mb-4">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2 text-left">Activity</th>
                            <th class="border px-4 py-2 text-center">Quantity</th>
                            <th class="border px-4 py-2 text-right">Unit Price</th>
                            <th class="border px-4 py-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction->activities as $activity)
                            <tr>
                                <td class="border px-4 py-2">{{ $activity->name }}</td>
                                <td class="border px-4 py-2 text-center">{{ $activity->pivot->quantity }}</td>
                                <td class="border px-4 py-2 text-right">₱{{ number_format($activity->amount, 2) }}</td>
                                <td class="border px-4 py-2 text-right">
                                    ₱{{ number_format($activity->amount * $activity->pivot->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-600 italic">No activities found for this transaction.</p>
        @endif


        @php
            $cartCollection = collect($cart); // Convert array to collection
        @endphp
    </div>



    {{-- ------------------------- Selected Activities (new) -------------------- --}}
    @if ($cartCollection->contains('type', 'activity'))
        <div class="bg-white rounded-lg shadow-md p-4">
            <h3 class="font-semibold text-lg text-gray-700 mb-1">Added Activities</h3>
            <hr>
            <div class="space-y-3">
                @foreach ($cart as $item)
                    @if ($item['type'] === 'activity')
                        <div class="flex items-center justify-between py-2 border-b border-gray-200 last:border-none"
                            wire:key="cart-item-{{ $item['activity_id'] }}">
                            <div class="flex flex-col">
                                <span class="font-semibold text-gray-900">{{ $item['activity_name'] }}</span>
                                <span class="text-sm text-gray-500">Quantity: {{ $item['quantity'] }}</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-md font-semibold text-green-700">
                                    ₱{{ number_format($item['amount'], 2) }}
                                </span>
                                <button type="button"
                                    wire:click="removeFromCart('{{ $item['type'] }}', {{ $item['activity_id'] }})"
                                    title="Remove Activity"
                                    class="bg-gray-200 text-gray-500 rounded-full w-5 h-5 flex items-center justify-center text-sm font-semibold leading-none hover:bg-red-300 hover:text-red-700 transition">
                                    <span class="leading-none translate-y-[-1px] font-bold">&times;</span>
                                </button>
                                {{-- <button type="button"
                                    wire:click="removeFromCart('{{ $item['type'] }}', {{ $item['activity_id'] }})"
                                    class="text-gray-500 hover:text-red-600  transition-colors" title="Remove Activity">
                                    <span class="text-xl leading-none">&times;</span>
                                </button> --}}
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            {{-- Button to Save Changes --}}
            <div class="flex justify-end mt-4">
                <button wire:click="register"
                    class="px-4 py-2 bg-green-700 bg-opacity-85 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150"
                    wire:loading.attr="disabled">
                    <div class="flex items-center justify-center">
                        <!-- Spinner -->
                        <span wire:loading wire:target="register" class="mr-2">
                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                </path>
                            </svg>
                        </span>
                        <!-- Button Text -->
                        <span wire:loading.remove wire:target="register">
                            Save Changes
                        </span>
                    </div>
                </button>
            </div>
        </div>
    @endif

    @error('cart')
        <span class="text-red-600">{{ $message }}</span>
    @enderror



    {{-- ------------------------- Select New Activity ------------------------- --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        @foreach ($availableActivities as $activity)
            <div
                class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200 mb-0 flex flex-col md:flex-row">
                <div class="md:w-1/2">
                    @if ($activity->image)
                        <img class="w-full h-48 object-cover" src="{{ asset('storage/' . $activity->image) }}"
                            alt="{{ $activity->name }}">
                    @else
                        <img class="w-full h-48 object-cover" src="{{ asset('images/rms-default.png') }}"
                            alt="{{ $activity->name }}">
                    @endif
                </div>

                <div class="md:w-1/2 p-4 flex flex-col justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800"> {{ $activity->name }}</h2>
                        <p class="text-gray-600 text-sm mb-4 text-justify">
                            @if (!empty($activity->description))
                                {{ $activity->description }}
                            @else
                                Try this activity only at our place!
                            @endif
                        </p>
                        <div class="text-lg font-semibold text-green-600 mb-2">
                            @if ($activity->amount == 0)
                                <span class="text-green-600 font-semibold">FREE</span>
                            @else
                                ₱{{ number_format($activity->amount, 2) }}
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-2">
                            <!-- Counter -->
                            <div class="flex flex-col">
                                <label for="quantity-{{ $activity->id }}"
                                    class="text-sm font-medium text-gray-700 mb-1">Quantity:</label>

                                <div class="flex items-center">
                                    <button type="button"
                                        wire:click.prevent="decrementActivity('{{ $activity->id }}')"
                                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-l px-2 py-1 focus:outline-none focus:shadow-outline">
                                        -
                                    </button>

                                    <span class="text-center w-16 py-1 bg-white border border-gray-300 rounded">
                                        {{ min($quantity[$activity->id] ?? 1, $total_pax) }}
                                    </span>

                                    @if (($quantity[$activity->id] ?? 1) < $total_pax)
                                        <button type="button"
                                            wire:click.prevent="incrementActivity('{{ $activity->id }}')"
                                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-r px-2 py-1 focus:outline-none focus:shadow-outline">
                                            +
                                        </button>
                                    @else
                                        <span class="text-red-500 text-xs ml-2">
                                            Maximum quantity reached (based on your total guests)
                                        </span>
                                    @endif
                                </div>

                            </div>
                            <button wire:click="addActivityToCart({{ $activity->id }})"
                                class="px-4 py-2 mt-auto flex bg-green-700 bg-opacity-85 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150"
                                wire:loading.attr="disabled">
                                <div class="flex items-center justify-center">
                                    <!-- Spinner -->
                                    <span wire:loading wire:target="addActivityToCart({{ $activity->id }})"
                                        class="mr-2">
                                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                            </path>
                                        </svg>
                                    </span>
                                    <!-- Button Text -->
                                    <span wire:loading.remove wire:target="addActivityToCart({{ $activity->id }})">
                                        Add Transaction
                                    </span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
