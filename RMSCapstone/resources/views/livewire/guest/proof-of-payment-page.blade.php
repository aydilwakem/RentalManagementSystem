<div class="max-w-xl mx-auto mt-10 p-8 bg-white rounded-lg shadow-md">

    <div class="flex justify-center mb-6">
        <img src="https://clubbalaiisabel.com/assets/img/logo.png" alt="Canopy Farm Logo" class="h-12" />
    </div>

    <h2 class="text-xl font-semibold text-center mb-2">We look forward to your stay with us!</h2>
    <p class="text-center text-gray-600 mb-6">Upload your proof of payment using this form</p>

    <form class="space-y-4" wire:submit.prevent="submitProofOfPayment">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Payment Methods --}}
            <div>
                <label for="payment_method_id" class="block mb-2 text-sm font-medium text-gray-900">Payment
                    Method</label>
                <select wire:model="payment_method_id" id="payment_method_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    <option value="">Select Payment Method</option>
                    @foreach ($payment_methods as $payment_method)
                        <option value="{{ $payment_method->id }}">{{ $payment_method->mode_of_payment_name }}</option>
                    @endforeach
                </select>
                @error('payment_method_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Transaction Number --}}
            <div>
                <label class="block mb-1 font-medium text-gray-700">Transaction Number<span
                        class="text-red-500">*</span></label>
                <input type="text" wire:model="transaction_id" id="transaction_id"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
                @error('transaction_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Payment Reference Number --}}
            <div class="mb-4">
                <label for="payment_reference_number" class="block mb-1 font-medium text-gray-700">
                    Payment Reference Number <span class="text-red-500">*</span>
                </label>

                <input type="text" id="payment_reference_number" wire:model="payment_reference_number"
                    class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-300 @error('payment_reference_number') border-red-500 @enderror"
                    placeholder="Enter your reference number" />

                @error('payment_reference_number')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>


        </div>


        {{-- Upload Payment Screenshot --}}
        <div class="sm:col-span-2">
            <label for="payment_screenshot" class="block mb-2 text-sm font-medium text-gray-900">Proof of
                Payment</label>

            <!-- Hidden file input -->
            <input id="payment_screenshot" type="file" accept="image/*" wire:model="payment_screenshot" class="hidden">

            <!-- Custom label for drag-and-drop or browse UI -->
            <label for="payment_screenshot">
                <div
                    class="w-full px-4 py-8 border-2 border-dashed border-gray-300 text-center rounded-md text-gray-500 cursor-pointer hover:border-blue-400">
                    <div class="mb-2">
                        <svg class="mx-auto w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 15a4 4 0 014-4h14M7 11V7a4 4 0 018 0v4m1 10H6a2 2 0 01-2-2V7a2 2 0 012-2h5.586a1 1 0 01.707.293l1.414 1.414a1 1 0 01.293.707V7">
                            </path>
                        </svg>
                    </div>
                    <p>Drag & drop a file or <span class="text-blue-500 underline">browse</span></p>
                </div>
            </label>

            <!-- Error Message -->
            @error('payment_screenshot')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <!-- Loading Indicator -->
            <div wire:loading wire:target="payment_screenshot" class="mt-2 text-blue-600">
                Uploading image...
            </div>

            <!-- Image Preview -->
            @if ($payment_screenshot && method_exists($payment_screenshot, 'temporaryUrl'))
                <div class="mt-2">
                    <img src="{{ $payment_screenshot->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-lg shadow">
                </div>
            @endif
        </div>

        <div class="mb-4">
            <label for="notes" class="block mb-1 font-medium text-gray-700">Notes</label>
            <input type="text" id="notes" wire:model="notes"
                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                placeholder="Enter any notes (optional)" />
            @error('notes')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="text-center">
            <button type="submit"
                class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition">Submit</button>
        </div>

    </form>

</div>