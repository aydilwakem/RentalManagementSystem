<div class="w-full flex justify-center">

    <div class="step-one w-full max-w-7xl px-4">


        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach ($activities as $activity)
            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white border rounded-xl overflow-hidden shadow-sm">
                    <div class="md:flex">

                        <!-- Image -->
                        <div class="mb-4">
                            <img src="{{ asset($activity->image ? 'storage/' . $activity->image : 'images/rms-default.png') }}"
                                class="w-full h-64 object-cover rounded-lg shadow-md">
                        </div>


                        <!-- Activity Info -->
                        <div class="md:w-2/3">

                            <h4 class="text-2xl font-semibold mb-2">{{ $activity->name }}</h4>
                            <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                <i class="fas fa-users mr-2"></i> Descriptin: {{
                                $activity->description }}
                            </p>
                            <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                <i class="fas fa-money mr-2"></i> Amount:
                                {{ $activity->amount }}
                            </p>


                        </div>

                        <!-- Activity Input -->
                        <div class="mt-4 md:mt-auto md:pt-4 flex flex-col justify-end">
                            <div class="flex flex-col sm:flex-row items-center gap-3">


                                <div class="flex items-center gap-2 w-full sm:w-auto">
                                    <label for="quantity-{{ $activity->id }}"
                                        class="text-sm font-medium text-gray-700">Quantity:</label>
                                    <select id="quantity-{{ $activity->id }}"
                                        wire:model.live="quantity.{{ $activity->id }}"
                                        class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        @for ($i = 1; $i <= 10; $i++) <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                    </select>
                                </div>

                                <div>
                                    <button wire:click="addActivityToCart({{ $activity->id }})""
                                        class=" px-4 py-2 mt-3 w-full bg-green-700 bg-opacity-85 hover:bg-green-800
                                        text-white rounded">
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


    </div>

</div>