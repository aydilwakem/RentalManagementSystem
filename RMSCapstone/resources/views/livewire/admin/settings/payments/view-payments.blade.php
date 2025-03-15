<div class="mt-10 px-4 lg:px-12 max-w-screen-xl mx-auto">
    <div class="bg-white overflow-hidden">

        <!-- Add Payment Method -->
        @can('payment-method-create')
            <div class="flex justify-between p-4">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition"
                    onclick="window.location.href='{{ route('admin.create-payment') }}'">
                    + Add Payment Method
                </button>
            </div>
        @endcan

        {{-- Display Session Message --}}
        @if (session('message'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg 
                                                                                                                                                                    {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                {{ session('message') }}
            </div>
        @endif

        <!-- Search Bar -->
        <div class="p-4">
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

        {{-- Method Card --}}
        <div class="p-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($paymentMethod as $method)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden w-full max-w-md mx-auto">
                        <a href="#">
                            <img class="w-full h-56 object-cover"
                                src="{{ asset('storage/' . $method->mode_of_payment_qr_image) }}"
                                alt="{{ $method->mode_of_payment_name }}" />
                        </a>
                        <div class="p-6 text-center">
                            <a href="#">
                                <h5 class="mb-3 text-2xl font-bold text-gray-900">{{ $method->mode_of_payment_name }}</h5>
                            </a>
                            <p class="text-gray-700">{{ $method->account_name }}</p>
                            <p class="text-gray-700">{{ $method->account_number }}</p>
                            <div class="mt-5 flex justify-center space-x-4">

                                <!-- View Icon -->
                                @can('payment-method-view')
                                    <i class="fas fa-eye text-blue-500 p-3 rounded-full border border-blue-500 cursor-pointer"
                                        wire:navigate
                                        href="{{ route('admin.view-payment', ['paymentMethod' => $method->id]) }}"></i>
                                @endcan

                                <!-- Edit Icon -->
                                @can('payment-method-edit')
                                    <i class="fas fa-edit text-green-500 p-3 rounded-full border border-green-500 cursor-pointer"
                                        wire:navigate
                                        href="{{ route('admin.edit-payment', ['paymentMethod' => $method->id]) }}">
                                    </i>
                                @endcan

                                <!-- Delete Icon -->
                                @can('payment-method-delete')
                                    <i class="fas fa-trash-alt text-red-500 p-3 rounded-full border border-red-500 cursor-pointer"
                                        wire:click="deletePaymentMethod({{ $method->id }})"></i>
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
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
            {{ $paymentMethod->links() }}
        </div>
    </div>
</div>
</div>