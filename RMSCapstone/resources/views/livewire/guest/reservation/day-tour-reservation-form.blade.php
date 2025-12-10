<div class="min-h-screen p-6 bg-yellow-50">
    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-green-700 font-bold tracking-wide uppercase mb-4 text-center text-3xl">
                Book your day tour
            </h1>
        </div>

        <!-- Step Header -->
        @include('livewire.guest.reservation.day-tour-step-header', ['currentStep' => $currentStep])

        <!-- Step Content -->
        <div>
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
                <x-ghost-button type="button" wire:click="decreaseStep">
                    Back
                </x-ghost-button>
            @else
                <div></div>
            @endif

            @if ($currentStep < 3)
                <x-button type="button" wire:click="increaseStep">
                    Next
                </x-button>
            @endif
        </div>
    </div>

    <!-- Guest Modal -->
    @if ($showGuestModal)
        <!-- Include the guest modal from your existing guest-detail.blade -->
        @include('livewire.guest.reservation.partials.guest-modal')
    @endif
</div>
