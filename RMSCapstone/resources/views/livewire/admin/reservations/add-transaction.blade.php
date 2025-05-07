<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Transaction') }}
        </h2>
    </x-slot>


    {{--------------------------- Selected Activities (existing) -----------------}}
    @if ($transaction->activities->isNotEmpty())
        <div class="overflow-x-auto bg-white p-6 rounded-lg shadow-md mb-6">
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




    {{--------------------------- Selected Activities (new) ----------------------}}
    @if ($cartCollection->contains('type', 'activity'))
        @foreach ($cart as $item)
            @if ($item['type'] === 'activity')
                <div class="flex justify-between items-center mb-2" wire:key="cart-item-{{ $item['activity_id'] }}">
                    <div class="text-gray-900">
                        <strong>Activity:</strong> {{ $item['activity_name'] }}<br>
                        <span class="text-sm">Quantity: {{ $item['quantity'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="text-md font-semibold text-green-700">
                            ₱{{ number_format($item['amount'], 2) }}
                        </div>
                        <button type="button" wire:click="removeFromCart('{{ $item['type'] }}', {{ $item['activity_id'] }})"
                            class="text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded-full w-5 h-5 flex items-center justify-center transition"
                            title="Remove Activity">
                            <span class="text-xl leading-none">&times;</span>
                        </button>
                    </div>
                </div>
            @endif
        @endforeach
    @endif

    @error('cart') <span class="text-red-600">{{ $message }}</span> @enderror





    {{--------------------------- Select New Activity ---------------------------}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach ($availableActivities as $activity)
            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white border rounded-xl overflow-hidden shadow-sm">
                    <div class="md:flex">

                        <!-- Activity Info -->
                        <div class="md:w-2/3">
                            <h4 class="text-2xl font-semibold mb-2">{{ $activity->name }}</h4>
                            <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                <i class="fas fa-users mr-2"></i> Description: {{ $activity->description }}
                            </p>
                            <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                <i class="fas fa-money mr-2"></i> Amount:
                                {{ $activity->amount }}
                            </p>
                        </div>

                        <!-- Activity Quantity Input -->
                        <div class="mt-4 md:mt-auto md:pt-4 flex flex-col justify-end">
                            <div class="flex flex-col sm:flex-row items-center gap-3">
                                <div class="flex items-center gap-2 w-full sm:w-auto">
                                    <label for="quantity-{{ $activity->id }}"
                                        class="text-sm font-medium text-gray-700">Quantity:</label>
                                    <select id="quantity-{{ $activity->id }}" wire:model.live="quantity.{{ $activity->id }}"
                                        class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <!-- Button to add activity in the cart -->
                                <div>
                                    <button
                                        wire:click="addActivityToCart({{ $activity->id }})""
                                                                                                                                                                                            class="
                                        px-4 py-2 mt-3 w-full bg-green-700 bg-opacity-85 hover:bg-green-800 text-white
                                        rounded">
                                        Add to Cart
                                    </button>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Button to Save Changes --}}
    <div>
        <button wire:click="register""
                                                        class=" px-4 py-2 mt-3 w-full bg-green-700 bg-opacity-85
            hover:bg-green-800 text-white rounded">
            Save Changes
        </button>
    </div>

</div>