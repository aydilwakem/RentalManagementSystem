<div class="min-h-screen flex items-center justify-center bg-gray-100">

    <form wire:submit.prevent="register">

        <!-- STEP 1 -->
        @if ($currentStep == 1)
            <div class="step-one">
                <div class="rounded-xl shadow bg-white">
                    <div class="bg-green-600 text-white text-lg font-semibold px-4 py-2 rounded-t-xl">STEP 1 - Book a Room
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <!-- Check-In Date -->
                            <div>
                                <label for="check_in_date" class="block mb-2 text-sm font-medium text-gray-900">Check-In
                                    Date</label>
                                <input type="date" id="check_in_date" wire:model.live="check_in_date"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                                @error('check_in_date')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Check-Out Date -->
                            <div>
                                <label for="check_out_date" class="block mb-2 text-sm font-medium text-gray-900">Check-Out
                                    Date</label>
                                <input type="date" id="check_out_date" wire:model.live="check_out_date"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                                @error('check_out_date')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Room Selection Cards -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($rooms as $room)
                                    <div class="border rounded-lg p-4 shadow-md transition 
                                                                                                                                                                                                                                                                                                                                           {{ $property_id == $room->id ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-200' }}
                                                                                                                                                                                                                                                                                                                                           {{ !$check_out_date ? 'cursor-not-allowed opacity-50' : 'cursor-pointer hover:shadow-lg' }}"
                                        @if($check_out_date) wire:click="$set('property_id', {{ $room->id }})" @endif>
                                        <h3 class="text-lg font-semibold text-gray-800">{{ $room->name_number }}</h3>
                                        <p class="text-sm text-gray-600">₱{{ number_format($room->amount, 2) }}</p>
                                        @if ($property_id == $room->id)
                                            <p class="text-sm text-green-600 mt-2 font-medium">Selected</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            @error('property_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror



                            <!-- Total Adults -->
                            <div>
                                <label for="total_adults" class="block mb-2 text-sm font-medium text-gray-900">Total
                                    Adults</label>
                                <input type="number" id="total_adults" wire:model.live="total_adults" min="1"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                                @error('total_adults')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Total Kids -->
                            <div>
                                <label for="total_kids" class="block mb-2 text-sm font-medium text-gray-900">Total
                                    Kids</label>
                                <input type="number" id="total_kids" wire:model.live="total_kids" min="0"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                                @error('total_kids')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <label for="pax" class="block mb-2 text-sm font-medium text-gray-900">Total
                                Pax: {{ $pax }}</label>
                            <label for="total_amount" class="block mb-2 text-sm font-medium text-gray-900">Total
                                Amount: {{ $total_amount}}</label>

                        </div>
                    </div>
                </div>
            </div>
        @endif


        <!-- STEP 2 -->
        @if ($currentStep == 2)
            <div class="step-two">
                <div class="rounded-xl shadow bg-white">
                    <div class="bg-green-600 text-white text-lg font-semibold px-4 py-2 rounded-t-xl">STEP 2 - Choose an
                        Activity</div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="activity_id"
                                        class="block mb-2 text-sm font-medium text-gray-900">Activity</label>
                                    <select wire:model.live="activity_id" id="activity_id"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                                        <option value="">Select Activity</option>
                                        @foreach ($activities as $activity)
                                            <option value="{{ $activity->id }}">{{ $activity->name }} - {{ $activity->amount }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('activity_id')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <label for="pax" class="block mb-2 text-sm font-medium text-gray-900">Total
                                    Pax: {{ $pax }}</label>
                                <label for="total_amount" class="block mb-2 text-sm font-medium text-gray-900">Total
                                    Amount: {{ $total_amount}}</label>

                            </div>
                        </div>
                    </div>
                </div>
        @endif

            <!-- STEP 3 -->
            @if ($currentStep == 3)
                <div class="step-three">
                    <div class="rounded-xl shadow bg-white">
                        <div class="bg-green-600 text-white text-lg font-semibold px-4 py-2 rounded-t-xl">STEP 3 - Check Out
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">First Name</label>
                                    <input type="text" class="w-full border rounded-md px-3 py-2" placeholder=""
                                        wire:model="first_name">

                                    @error('first_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror

                                    <label class="block text-sm font-medium mb-1 mt-4">Last Name</label>
                                    <input type="text" class="w-full border rounded-md px-3 py-2" placeholder=""
                                        wire:model="last_name">

                                    @error('last_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror

                                    <label class="block text-sm font-medium mb-1 mt-4">Email</label>
                                    <input type="email" class="w-full border rounded-md px-3 py-2" placeholder=""
                                        wire:model="email">

                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


            <!-- Action Buttons -->
            <div class="flex justify-end gap-2 pt-4">

                @if ($currentStep == 1)
                    <div></div>
                @endif

                {{-- Back Button --}}
                @if ($currentStep == 2 | $currentStep == 3)
                    <button type="button" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md text-sm"
                        wire:click="decreaseStep()">Back</button>
                @endif

                {{-- Next Button --}}
                @if ($currentStep == 1 | $currentStep == 2)
                    <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded-md text-sm"
                        wire:click="increaseStep()">Next</button>
                @endif

                {{-- Submit Button --}}
                @if ($currentStep == 3)
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md text-sm">Submit</button>
                @endif

            </div>

    </form>
</div>