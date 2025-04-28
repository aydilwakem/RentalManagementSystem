<div>

    <!-- First Name -->
    <div>
        <label for="first_name" class="block mb-1">First Name</label>
        <input type="text" wire:model="first_name" id="first_name" class="w-full border p-2 rounded" />
        @error('first_name')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Middle Name -->
    <div>
        <label for="middle_name" class="block mb-1">Middle Name</label>
        <input type="text" wire:model="middle_name" id="middle_name" class="w-full border p-2 rounded" />
        @error('middle_name')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>


    <!-- Last Name -->
    <div>
        <label for="last_name" class="block mb-1">Last Name</label>
        <input type="text" wire:model="last_name" id="last_name" class="w-full border p-2 rounded" />
        @error('last_name')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Email -->
    <div>
        <label for="email" class="block mb-1">Email</label>
        <input type="email" wire:model="email" id="email" class="w-full border p-2 rounded" />
        @error('email')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Contact Number -->
    <div>
        <label for="contact_number" class="block mb-1">Contact Number</label>
        <input type="text" wire:model="contact_number" id="contact_number" class="w-full border p-2 rounded" />
        @error('contact_number')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Country -->
    <div>
        <label for="country" class="block mb-1">Country</label>
        <input type="text" wire:model="country" id="country" class="w-full border p-2 rounded" />
        @error('country')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Heard From -->
    <div>
        <label for="heard_from" class="block mb-2 text-sm font-medium text-gray-900">Property
            Status</label>
        <select wire:model="heard_from" id="heard_from"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
            <option value="Facebook">Facebook</option>
            <option value="Instagram">Instagram</option>
            <option value="Tiktok">Tiktok</option>
            <option value="Youtube">Youtube</option>
            <option value="Google">Google</option>
        </select>
        @error('heard_from')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>



</div>