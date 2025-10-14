<div class="min-h-screen p-6 bg-gray-50">
    <div class="max-w-6xl mx-auto">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-green-700">Book Your Day Tour</h1>
            <p class="text-gray-600 mt-2">Experience the best of our day tour packages</p>
        </div>

        <!-- Step Header -->
        @include('livewire.guest.reservation.day-tour-step-header', ['currentStep' => $currentStep])

        <!-- Step Content -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            @if ($currentStep == 1)
                @include('livewire.guest.reservation.day-tour-step-one')
            @elseif ($currentStep == 2)
                @include('livewire.guest.reservation.day-tour-step-two')
            @elseif ($currentStep == 3)
                @include('livewire.guest.reservation.day-tour-step-three')
            @endif
        </div>

        <!-- Navigation -->
        <div class="flex justify-between mt-6">
            @if ($currentStep > 1)
                <button type="button" wire:click="decreaseStep" 
                    class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    Back
                </button>
            @else
                <div></div>
            @endif

            @if ($currentStep < 3)
                <button type="button" wire:click="increaseStep" 
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Next
                </button>
            @endif
        </div>
    </div>

    <!-- Guest Modal -->
    @if ($showGuestModal)
        <!-- Include the guest modal from your existing guest-detail.blade -->
        @include('livewire.guest.reservation.partials.guest-modal')
    @endif
</div>