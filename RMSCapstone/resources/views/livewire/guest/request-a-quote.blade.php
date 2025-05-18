<div class="container mx-auto py-8 px-4">
    <!-- Page Title -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-semibold text-green-700">Request a quote for your event</h1>
        <p class="text-lg text-gray-600 mt-2">Provide your details below, and we'll get back to you with a quote!</p>
    </div>

    {{-- For sessionn messages --}}
    @if (session('message'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg
                    {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
        {{ session('message') }}
    </div>
    @endif

    <!-- Request a Quote Form -->
    <div class="max-w-2xl mx-auto bg-white p-8 mb-6 rounded-lg shadow-md border">
        <form wire:submit.prevent="requestQuote">

            <div class="grid grid-cols-2 gap-4 mb-4">
                <!-- Company Name -->
                <div>
                    <label for="company-name" class="block text-sm font-medium text-gray-700">Company Name</label>
                    <input type="text" id="company-name" name="company_name" wire:model="company_name"
                        class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Enter your company name" required>

                    @error('company_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contact Person -->
                <div>
                    <label for="contact-person" class="block text-sm font-medium text-gray-700">Contact Person</label>
                    <input type="text" id="contact-person" name="contact_person" wire:model="contact_person"
                        class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Enter the contact person's name" required>
                    @error('contact_person')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" id="email" name="email" wire:model="email"
                        class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Enter your email address" required>
                    @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contact Number -->
                <div class="mb-4">
                    <label for="contact-number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                    <input type="tel" id="contact-number" name="contact_number" wire:model="contact_number"
                        class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Enter your contact number" required>
                    @error('contact_number')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Selected Hall --}}
            <div class="mb-4">
                <label for="hall" class="block text-sm font-medium text-gray-700">Select Event Hall</label>
                <select id="hall" wire:model="selected_hall"
                    class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Choose a hall</option>
                    @foreach ($halls as $hall)
                    <option value="{{ $hall->id }}"> {{ $hall->name_number }}
                    </option>
                    @endforeach
                </select>
                @error('selected_hall')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Event Start and End Dates -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="event-start" class="block text-sm font-medium text-gray-700">Event Start Date</label>
                    <input type="datetime-local" id="event-start" name="event_start" wire:model="event_start"
                        class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        required>
                    @error('event_start')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="event-end" class="block text-sm font-medium text-gray-700">Event End Date</label>
                    <input type="datetime-local" id="event-end" name="event_end" wire:model="event_end"
                        class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        required>
                    @error('event_end')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Event Type Dropdown -->
            <div class="mb-4">
                <label for="event-type" class="block text-sm font-medium text-gray-700">Event Type</label>
                <select id="event-type" name="event_type" wire:model="event_type"
                    class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                    required>
                    <option value="">Select Event Type</option>
                    <option value="Wedding">Wedding</option>
                    <option value="Birthday">Birthday</option>
                    <option value="Team Building">Team Building</option>
                    <option value="other">Other (Please specify)</option>
                </select>
                @error('event_type')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- If 'Other' Event Type is selected, show additional input field -->
            <div class="mb-4 hidden" id="other-event-type">
                <label for="other-event-type-text" class="block text-sm font-medium text-gray-700">Specify Event
                    Type</label>
                <input type="text" id="other-event-type-text" name="other_event_type" wire:model="other_event_type"
                    class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                    placeholder="Enter event type">
                @error('other_event_type')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Additional Requests -->
            <div class="mb-4">
                <label for="additional-requests" class="block text-sm font-medium text-gray-700">Additional
                    Requests</label>
                <textarea id="additional-requests" name="additional_requests" rows="4" wire:model="additional_requests"
                    class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 resize-none"
                    placeholder="Any additional details or special requests"></textarea>
                @error('additional_requests')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center">
                <x-button type="submit" class="w-auto mx-auto text-center transition duration-300" icon="fas fa-check">
                    Request Quote
                </x-button>
            </div>

        </form>
    </div>
</div>

<script>
    const eventTypeSelect = document.getElementById('event-type');
    const otherEventTypeDiv = document.getElementById('other-event-type');

    eventTypeSelect.addEventListener('change', function () {
        if (this.value === 'other') {
            otherEventTypeDiv.classList.remove('hidden');
        } else {
            otherEventTypeDiv.classList.add('hidden');
        }
    });
</script>