<div class="min-h-[550px] container mx-auto p-2 max-w-full">
    @if ($paymentMethod->isEmpty())
        <!-- Empty Page Message -->
        <div class="text-center py-10">
            <p class="text-gray-500 text-lg font-semibold">No payment methods yet.<br> Click "Create Payment Methods" to
                add
                a new payment method.</p>
            <x-button class="mt-4" href="{{ route('admin.create-payment') }}" icon="fas fa-plus">
                Create Payment Method
            </x-button>
        </div>
    @else
        <div class="p-3">
            <div class="flex items-center justify-between">
                <div class="w-48">
                    <div class="relative w-full">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search" required
                            class="w-full pl-10 p-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    @can('room-create')
                        <x-button icon="fas fa-plus" href="{{ route('admin.create-payment') }}">
                            New Payment Method
                        </x-button>
                    @endcan

                    @can('room-soft-delete')
                        <x-button
                            class="!bg-gray-600 hover:!bg-gray-700 focus:ring focus:!ring-gray-600 focus:!ring-offset-2"
                            icon="fas fa-trash" href="{{ route('admin.deleted-payments') }}">
                            Deleted Payment Methods
                        </x-button>
                    @endcan
                </div>
            </div>

        </div>
        {{-- Display Session Message --}}
        @if (session('message'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg
                {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                {{ session('message') }}
            </div>
        @endif

        <!-- View Icon -->
        <div class="p-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($paymentMethod as $method)
                    <div x-data="{ blurred: true }"
                        class="bg-white rounded-lg shadow-md overflow-hidden w-full max-w-md mx-auto">

                        <div class="relative">
                            <!-- QR Image -->
                            <img :class="blurred ? 'blur' : ''" class="w-full h-56 object-cover transition duration-300"
                                src="{{ asset($method->mode_of_payment_qr_image ? 'storage/' . $method->mode_of_payment_qr_image : 'images/rms-default.png') }}"
                                alt="{{ $method->mode_of_payment_name }}" />

                            <!-- Toggle Blur Button -->
                            <button @click="blurred = !blurred"
                                class="absolute top-2 right-2 text-white bg-gray-500 hover:bg-gray-600 p-1 rounded-full transition">
                                <i :class="blurred ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
                            </button>
                        </div>

                        <div class="p-6 text-center">
                            <h5 class=" text-2xl font-bold text-gray-900">
                                {{ $method->mode_of_payment_name }}
                            </h5>
                            <p class="text-gray-700">{{ $method->account_name }}</p>
                            <p class="text-gray-700">{{ $method->account_number }}</p>

                            <div class="mt-3 flex justify-center space-x-3">
                                @can('payment-method-view')
                                    <i class="fas fa-eye text-blue-500 p-3 rounded-full border border-blue-500 cursor-pointer"
                                        wire:navigate
                                        href="{{ route('admin.view-payment', ['paymentMethod' => $method->id]) }}"></i>
                                @endcan

                                @can('payment-method-edit')
                                    <i class="fas fa-edit text-yellow-500 p-3 rounded-full border border-yellow-500 cursor-pointer"
                                        wire:navigate
                                        href="{{ route('admin.edit-payment', ['paymentMethod' => $method->id]) }}">
                                    </i>
                                @endcan

                                @can('payment-method-delete')
                                    <i class="fas fa-trash-alt text-red-500 p-3 rounded-full border border-red-500 cursor-pointer"
                                        wire:click="confirmDelete({{ $method->id }})" wire:loading.attr="disabled">
                                    </i>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        {{-- Pagination --}}
        <div class="py-4 px-3">
            <div class="flex ">
                <div class="flex space-x-4 items-center mb-3">
                    <label class="w-32 text-sm font-medium text-gray-900">Per Page</label>
                    <select wire:model.live="perPage"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
            {{ $paymentMethod->links() }}
        </div>

        <!-- Delete Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmItemDelete">
            <x-slot name="title">
                {{ __('Delete Payment Method') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this item?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deletePaymentMethod({{ $method->id }})"
                    wire:loading.attr="disabled">
                    {{ __('Delete Payment Method') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>
    @endif
</div>
