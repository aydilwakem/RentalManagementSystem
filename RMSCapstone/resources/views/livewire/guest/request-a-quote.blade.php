<div class="container mx-auto py-12 px-4 sm:px-6 lg:px-8">

    <!-- Page Header -->
    <div class="text-center max-w-3xl mx-auto mb-10">
        <h1 class="text-3xl md:text-4xl font-extrabold text-green-700 tracking-tight">
            Plan Your Perfect Event
        </h1>
        <p class="text-lg text-gray-600 mt-3 leading-relaxed">
            Tell us about your upcoming event at Canopy Farm, and our team will craft a personalized quote just for you.
        </p>
    </div>

    <!-- Session Messages -->
    @if (session('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 max-w-md w-full px-6">

            <div class="rounded-lg shadow-lg p-4 flex items-center gap-3 border-l-4 {{ session('alert-type') === 'success' ? 'bg-green-50 border-green-500 text-green-800' : 'bg-red-50 border-red-500 text-red-800' }}">
                <i class="{{ session('alert-type') === 'success' ? 'fas fa-check-circle text-green-500' : 'fas fa-exclamation-circle text-red-500' }} text-xl"></i>
                <span class="font-medium text-sm">{{ session('message') }}</span>
                <button @click="show = false" class="ml-auto text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Form Container -->
    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">

        <!-- Top Bar -->
        <div class="h-2 bg-green-600"></div>

        <div class="p-8 md:p-10">
            <form wire:submit.prevent="requestQuote" class="space-y-8">

                <!-- Contact Information -->
                <div>
                    <h3 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-2 mb-6 flex items-center gap-2">
                        <i class="fas fa-user-circle text-green-600"></i> Contact Details
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Company Name -->
                        <div>
                            <label for="company-name" class="block text-sm font-semibold text-gray-700 mb-1">Company / Organization <span class="text-red-500">*</span></label>
                            <input type="text" id="company-name" wire:model="company_name" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all placeholder-gray-400"
                                placeholder="Ex. ABC Corp or N/A">
                            @error('company_name') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Contact Person -->
                        <div>
                            <label for="contact-person" class="block text-sm font-semibold text-gray-700 mb-1">Contact Person <span class="text-red-500">*</span></label>
                            <input type="text" id="contact-person" wire:model="contact_person" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all placeholder-gray-400"
                                placeholder="Ex. Juan Dela Cruz">
                            @error('contact_person') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" id="email" wire:model="email" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all placeholder-gray-400"
                                placeholder="Ex. juan.delacruz@example.com">
                            @error('email') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="contact-number" class="block text-sm font-semibold text-gray-700 mb-1">Mobile Number <span class="text-red-500">*</span></label>
                            <input type="tel" id="contact-number" wire:model="contact_number" required
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')" inputmode="numeric" maxlength="11"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all placeholder-gray-400"
                                placeholder="Ex. 09123456789">
                            @error('contact_number') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Event Details -->
                <div>
                    <h3 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-2 mb-6 flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-green-600"></i> Event Information
                    </h3>

                    <div class="space-y-6">
                        <!-- Hall Selection -->
                        <div>
                            <label for="hall" class="block text-sm font-semibold text-gray-700 mb-1">Preferred Venue <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select id="hall" wire:model="selected_hall" required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all appearance-none bg-white">
                                    <option value="">Select an Event Hall</option>
                                    @foreach ($halls as $hall)
                                        <option value="{{ $hall->id }}">{{ $hall->name_number }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            @error('selected_hall') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="event-start" class="block text-sm font-semibold text-gray-700 mb-1">Start Date & Time <span class="text-red-500">*</span></label>
                                <input type="datetime-local" id="event-start" wire:model.live="event_start" required
                                    min="{{ \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d\TH:i') }}"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-gray-600">
                                @error('event_start') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="event-end" class="block text-sm font-semibold text-gray-700 mb-1">End Date & Time <span class="text-red-500">*</span></label>
                                <input type="datetime-local" id="event-end" wire:model.live="event_end" required
                                    min="{{ \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d\TH:i') }}"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-gray-600">
                                @error('event_end') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Event Type -->
                        <div>
                            <label for="event-type" class="block text-sm font-semibold text-gray-700 mb-1">Type of Event <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select id="event-type" wire:model="event_type" required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all appearance-none bg-white">
                                    <option value="">Select Event Type</option>
                                    <option value="Wedding">Wedding</option>
                                    <option value="Birthday">Birthday</option>
                                    <option value="Team Building">Team Building</option>
                                    <option value="other">Other</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            @error('event_type') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Other Type -->
                        <div x-data="{ type: @entangle('event_type') }" x-show="type === 'other'" x-transition class="mt-3">
                            <label for="other-event-type-text" class="block text-sm font-semibold text-gray-700 mb-1">Please Specify</label>
                            <input type="text" id="other-event-type-text" wire:model="other_event_type"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                placeholder="Ex. Reunion, Seminar">
                            @error('other_event_type') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Additional Requests -->
                        <div>
                            <label for="additional-requests" class="block text-sm font-semibold text-gray-700 mb-1">Special Requests / Notes</label>
                            <textarea id="additional-requests" wire:model="additional_requests" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all resize-none placeholder-gray-400"
                                placeholder="Any dietary restrictions, setup requirements, or questions?"></textarea>
                            @error('additional_requests') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-3 flex justify-between">
                    <x-ghost-button href="{{ route('guest.event-halls') }}">
                        Back to Event Halls
                    </x-ghost-button>
                    <x-button type="submit"
                        class="px-8 py-3 bg-green-700 hover:bg-green-800 text-white font-bold shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">

                        <span wire:loading.remove wire:target="requestQuote">
                            Submit Request <i class="fas fa-paper-plane ml-1"></i>
                        </span>

                        <span wire:loading wire:target="requestQuote" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z"></path>
                            </svg>
                        </span>
                    </x-button>
                </div>

            </form>
        </div>
    </div>
</div>

