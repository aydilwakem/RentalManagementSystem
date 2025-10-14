<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('Create Promo Code') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
        ['label' => 'Promo Codes', 'url' => route('admin.view-promo-codes')],
        ['label' => 'Create Promo Code', 'url' => route('admin.create-promo-code')],
    ]" />
    </x-slot>

    {{-- Body Container --}}
    <div>
        <div
            class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Add New Promo Code</h2>

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
                            <label for="code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                                Promo Code <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">
                                <input type="text" wire:model="code" id="code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 pr-24
                                    dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                                    placeholder="Ex. RAINY500">

                                <!-- Auto Generate -->
                                <span wire:click="generateCode"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-green-600 hover:underline cursor-pointer select-none dark:text-green-300">
                                    Auto Generate
                                </span>
                            </div>

                            @error('code')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>


                        <!-- Code Description -->
                        <div>
                            <label for="description"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Promo
                                Name/Description</label>
                            <input type="text" wire:model="description" id="description" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                                placeholder="Ex. P500 off for rainy day season reservations">
                            @error('description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Discount Type -->
                        <div>
                            <label for="discount_type"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                                Discount Type <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="discount_type" id="discount_type" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
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
                        <label for="discount_value"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Discount Value <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">
                            {{-- Symbol when type is selected --}}
                            @if ($discount_type === 'fixed')
                            <span
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-200">₱</span>
                            @elseif ($discount_type === 'percentage')
                            <span
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-200">%</span>
                            @endif

                            {{-- Placeholders based on type --}}
                            <input type="number" wire:model="discount_value" id="discount_value" onwheel="this.blur()"
                                class="
                            @if ($discount_type === 'fixed') pl-8
                            @elseif ($discount_type === 'percentage') pr-8 @endif
                            bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                                placeholder="{{ $discount_type === 'percentage' ? 'Ex. 10%' : ($discount_type === 'fixed' ? 'Ex. ₱500' : 'Enter discount value') }}">
                        </div>

                        @error('discount_value')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>


                    <!-- Max Uses -->
                    <div>
                        <label for="max_uses" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Maximum Uses (Optional)
                        </label>
                        <input type="number" wire:model="max_uses" id="max_uses" onwheel="this.blur()" class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400" min="1"
                            max="30" placeholder="Ex. 10">
                        @error('max_uses')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Per User Limit Count -->
                    <div>
                        <label for="per_user_limit"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Limit Per
                            User <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="per_user_limit" id="per_user_limit" onwheel="this.blur()"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. 1">
                        @error('per_user_limit')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Minimum Booking Amount -->
                    <div>
                        <label for="min_booking_amount"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Minimum
                            Booking Amount (Optional)</label>
                        <input type="text" wire:model="min_booking_amount" id="min_booking_amount" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. P3,000.00">
                        @error('min_booking_amount')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Assigned Room Category -->
                    <div>
                        <label for="property_category_id"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Apply
                            to <span class="text-red-500">*</span></label>
                        <select wire:model="property_category_id" id="property_category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                            <option value="">Select Room Category</option>
                            @foreach ($propertyCategories as $propertyCategory)
                            <option value="{{ $propertyCategory->id }}">{{ $propertyCategory->name }}</option>
                            @endforeach
                        </select>
                        @error('property_category_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Promo Status -->
                    <div>
                        <label for="is_active" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Promo Status <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <span class="text-gray-700 dark:text-gray-200">Inactive</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="is_active" id="is_active" value="1"
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-green-500 rounded-full peer peer-checked:bg-green-600 transition
                                   ">
                                </div>
                                <div
                                    class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5">
                                </div>
                            </label>
                            <span class="text-gray-700 dark:text-gray-200">Active</span>
                        </div>
                        @error('is_active')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Has Expiration -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Promo Expiration <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <span class="text-gray-700 dark:text-gray-200">No Expiration</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="has_expiration" id="has_expiration" value="1"
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-green-500 rounded-full peer peer-checked:bg-green-600 transition
                                    ">
                                </div>
                                <div
                                    class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5">
                                </div>
                            </label>
                            <span class="text-gray-700 dark:text-gray-200">Has Expiration</span>
                        </div>
                        @error('has_expiration')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>


                    @if ($has_expiration)
                    <!-- Duration Days -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Duration</label>
                        <div
                            class="text-gray-700 text-sm bg-gray-50 border border-gray-300 rounded-lg px-4 py-2 cursor-not-allowed">
                            {{ $duration_days ? $duration_days . ' Day' . ($duration_days > 1 ? 's' : '') : '—' }}
                        </div>
                    </div>


                    <!-- Start Date -->
                    <div>
                        <label for="start_date"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Start
                            Date <span class="text-red-500">*</span></label>
                        <input type="date" wire:model.live="start_date" id="start_date" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @error('start_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            End Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model.live="end_date" id="end_date" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @error('end_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    @endif

                    <!-- Stay Date Range Section -->
<div class="col-span-2 mt-6">
    <h3 class="text-lg font-bold text-green-800 mb-3 dark:text-green-300">Valid Stay Dates (Optional)</h3>
    <p class="text-sm text-gray-600 mb-4 dark:text-gray-300">
        If set, this promo will only apply to reservations with check-in/check-out dates within this range.
        Leave blank to apply to all stay dates.
    </p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Valid Stay Start Date -->
        <div>
            <label for="stay_start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                Valid Stay Start Date
            </label>
            <input type="date" wire:model="stay_start_date" id="stay_start_date" 
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 
                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
            @error('stay_start_date')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Valid Stay End Date -->
        <div>
            <label for="stay_end_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                Valid Stay End Date
            </label>
            <input type="date" wire:model="stay_end_date" id="stay_end_date" 
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 
                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
            @error('stay_end_date')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

                </div>

                <!-- Buttons -->
                <div class="flex justify-between items-center space-y-2 mt-6">
                    <x-ghost-button onclick="history.back()" type="button">
                        Cancel
                    </x-ghost-button>
                    <x-button wire:loading.attr="disabled" wire:click="confirmCreate">
                        Create Promo Code
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
