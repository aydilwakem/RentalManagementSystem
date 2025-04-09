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
                        <div>
                            <label for="room_id" class="block mb-2 text-sm font-medium text-gray-900">Room</label>
                            <select wire:model="room_id" id="room_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                                <option value="">Select Room</option>
                                @foreach ($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }} - {{ $room->base_rate }}</option>
                                @endforeach
                            </select>
                            @error('room_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
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
                                <select wire:model="activity_id" id="activity_id"
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

                                <label class="block text-sm font-medium mb-1 mt-4">Contact Number</label>
                                <input type="text" class="w-full border rounded-md px-3 py-2" placeholder=""
                                    wire:model="contact_number">
                                @error('contact_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- STEP 4 -->
            @if ($currentStep == 4)
            <div class="step-four">
                <div class="rounded-xl shadow bg-white">
                    <div class="bg-green-600 text-white text-lg font-semibold px-4 py-2 rounded-t-xl">STEP 4 - Payment
                        Details</div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <!-- Payment Reference Number -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Payment Reference Number</label>
                                <input type="text" class="w-full border rounded-md px-3 py-2" placeholder=""
                                    wire:model="payment_reference_number">

                                @error('payment_reference_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Payment Screenshot -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Payment Screenshot</label>

                                @if ($this->payment_screenshot)
                                <div>
                                    <label>Uploaded Screenshot:</label>
                                    <img src="{{ asset('storage/' . $this->payment_screenshot) }}"
                                        alt="Payment Screenshot" width="200">
                                </div>
                                @endif

                                <input type="file" class="w-full border rounded-md px-3 py-2"
                                    wire:model="payment_screenshot">

                                @error('payment_screenshot')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Terms -->
                            <div class="col-span-2">
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" id="terms" wire:model="terms"
                                        class="border-gray-300 rounded">
                                    <span class="text-sm">You must agree with our <a href="#"
                                            class="text-blue-600 underline">Terms and Condition</a></span>

                                    @error('terms')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </label>
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
                @if ($currentStep == 2 | $currentStep == 3 | $currentStep == 4)
                <button type="button" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md text-sm"
                    wire:click="decreaseStep()">Back</button>
                @endif

                {{-- Next Button --}}
                @if ($currentStep == 1 | $currentStep == 2 | $currentStep == 3)
                <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded-md text-sm"
                    wire:click="increaseStep()">Next</button>
                @endif

                {{-- Submit Button --}}
                @if ($currentStep == 4)
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md text-sm">Submit</button>
                @endif

            </div>

    </form>
</div>