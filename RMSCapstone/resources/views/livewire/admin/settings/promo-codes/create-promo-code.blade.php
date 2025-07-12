<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Promo Code') }}
        </h2>
    </x-slot>

    {{-- Body Container --}}
    <div class="py-3">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center">Add New Promo Code</h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.view-promo-codes') }}'" wire:navigate
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            {{-- Session Message --}}
            @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
            @endif

            <!-- Form Container -->
            <form wire:submit.prevent="">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <!-- Promo Details -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 col-span-2">

                        <!-- Promo Name -->
                        <div>
                            <label for="code" class="block mb-2 text-sm font-medium text-gray-900">
                                Promo Code Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="code" id="code"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5"
                                placeholder="Ex. RAINY500">
                            @error('code')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Code Description -->
                        <div>
                            <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Promo
                                Description</label>
                            <input type="text" wire:model="description" id="description"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5"
                                placeholder="Ex. P500 off for rainy day season reservations">
                            @error('description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Discount Type -->
                        <div>
                            <label for="discount_type" class="block mb-2 text-sm font-medium text-gray-900">
                                Discount Type <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="discount_type" id="discount_type" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="">Select Discount Type</option>
                                <option value="fixed">Fixed</option>
                                <option value="percentage">Percentage</option>
                            </select>
                            @error('discount_type')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <!-- Discount Value -->
                    <div>
                        <label for="discount_value" class="block mb-2 text-sm font-medium text-gray-900">Discount
                            Value</label>
                        <input type="number" wire:model="discount_value" id="discount_value"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5"
                            placeholder="500">
                        @error('discount_value')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Max Uses -->
                    <div>
                        <label for="max_uses" class="block mb-2 text-sm font-medium text-gray-900">
                            Maximum Uses <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="max_uses" id="max_uses"
                            class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5"
                            min="1" max="30" placeholder="Ex. 10">
                        @error('max_uses')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Per User Limit Count -->
                    <div>
                        <label for="per_user_limit" class="block mb-2 text-sm font-medium text-gray-900">Per
                            User Limit</label>
                        <input type="number" wire:model="per_user_limit" id="per_user_limit"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5"
                            placeholder="Ex. 0">
                        @error('per_user_limit')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Minimum Booking Amount -->
                    <div>
                        <label for="min_booking_amount" class="block mb-2 text-sm font-medium text-gray-900">Minimum
                            Booking Amount</label>
                        <input type="text" wire:model="min_booking_amount" id="min_booking_amount"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5"
                            placeholder="Ex. P3000">
                        @error('min_booking_amount')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900">Start Date</label>
                        <input type="date" wire:model.live="start_date" id="start_date" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @error('start_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900">
                            End Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model.live="end_date" id="end_date" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @error('end_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Duration Days --}}
                    <div>
                        <label for="duration_days" class="block mb-2 text-sm font-medium text-gray-900">
                            Duration Days</label>
                        <input type="text" wire:model="duration_days" id="duration_days"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5"
                            placeholder="Ex. 30">
                        @error('duration_days')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Has Expiration -->
                    <div>
                        <label for="has_expiration" class="block mb-2 text-sm font-medium text-gray-900">
                            Promo Expiration <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="has_expiration" id="has_expiration" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="">Select from Option</option>
                            <option value="1">Has Expiration</option>
                            <option value="0">No Expiration</option>
                        </select>
                        @error('has_expiration')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Promo Active --}}
                    <div>
                        <label for="is_active" class="block mb-2 text-sm font-medium text-gray-900">
                            Is Promo Active<span class="text-red-500">*</span>
                        </label>
                        <select wire:model="is_active" id="is_active" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="">Select from Option</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        @error('is_active')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Assigned Room Category --}}
                    <div>
                        <label for="property_category_id"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Room
                            Category <span class="text-red-500">*</span></label>
                        <select wire:model="property_category_id" id="property_category_id" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="">Select Room Category</option>
                            @foreach ($propertyCategories as $propertyCategory)
                            <option value="{{ $propertyCategory->id }}">{{ $propertyCategory->name }}</option>
                            @endforeach
                        </select>
                        @error('property_category_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <!-- Buttons -->
                <div class="flex justify-between items-center space-y-2 mt-6">
                    <x-ghost-button onclick="history.back()" type="button">
                        Cancel
                    </x-ghost-button>
                    <x-button wire:loading.attr="disabled" wire:click="confirmCreate">
                        Add Promo Code
                    </x-button>
                </div>
            </form>

            <!-- Create Confirmation Modal -->
            <x-dialog-modal wire:model.live="confirmCreateItem">
                <x-slot name="title">
                    {{ __('Create Promo Code') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to add this promo code?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmCreateItem', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-button class="ms-3 bg-green text-white" wire:click="savePromoCode" wire:loading.attr="disabled">
                        {{ __('Create Promo Code') }}
                    </x-button>
                </x-slot>
            </x-dialog-modal>
        </div>
    </div>
</div>