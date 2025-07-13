<div class="mx-4 sm:mx-auto bg-white dark:bg-gray-700 dark:border-gray-600 rounded-2xl p-8 max-w-4xl">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Edit User') }}
        </h2>
    </x-slot>
    <h2 class="mb-4 text-xl font-bold text-gray-900 text-center dark:text-white">Edit User</h2>
    <form wire:submit.prevent="">
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

            <!-- Name -->
            <div class="sm:col-span-2">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full Name</label>
                <p class="text-gray-900 text-sm dark:text-gray-200">{{ $name }} {{ $middle_name }} {{ $last_name }}</p>
            </div>

            <!-- Email -->
            <div class="sm:col-span-2">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                <p class="text-gray-900 text-sm dark:text-gray-200">{{ $email }}</p>
            </div>

            <!-- Password -->
            {{-- <div class="sm:col-span-2">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                <input type="password" wire:model="password" id="password"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="">
                @error('password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div> --}}

            <!-- Role Selection -->
            <div class="sm:col-span-2">
                <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Role</label>
                <select id="role" wire:model="selectedRole"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                    dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                    <option value={{ is_null($selectedRole) ? 'selected' : '' }}>Select a role
                    </option>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
                @error('selectedRole')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-between items-center space-y-2 mt-8">
            <x-button onclick="history.back()" type="button"
                class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                Cancel
            </x-button>
            <x-button type="submit" wire:click="confirmEdit({{ $user->id }})" wire:loading.attr="disabled">
                Update User
            </x-button>
        </div>
    </form>


    <!-- Edit Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmEditItem">
        <x-slot name="title">
            {{ __('Update User') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to save changes to this user?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmEditItem', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3 bg-green text-white" wire:click="updateUser({{ $user->id }})">
                {{ __('Save Changes') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

</div>
