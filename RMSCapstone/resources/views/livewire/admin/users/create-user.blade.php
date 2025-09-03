<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('Create User') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Users', 'url' => route('admin.manage-users')],
            ['label' => 'Create User', 'url' => route('admin.create-user')],
        ]" />
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div
            class="mx-auto max-w-3xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Add New user</h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.manage-users') }}'" wire:navigate
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Form container -->
            <form wire:submit.prevent="">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Full
                            Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" id="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. Juan Dela Cruz" required>
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Email
                            <span class="text-red-500">*</span></label>
                        <input type="email" wire:model="email" id="email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. juan.delacruz@example.com"
                            pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,5}$" required>
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Role Dropdown -->
                    <div class="sm:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Select Role <span
                                class="text-red-500">*</span></label>
                        <select wire:model="selectedRole"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                            <option value="">-- Choose a Role --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                        @error('selectedRole')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="sm:col-span-2 relative">
                        <label for="password"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Password <span
                                class="text-red-500">*</span></label>
                        <input type="password" wire:model="password" id="password"
                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Enter password" required>
                        <!-- Toggle button -->
                        <div class="absolute inset-y-0 mt-6 right-0 flex items-center pr-5">
                            <i id="togglePassword" class="fa-solid fa-eye text-gray-400 cursor-pointer"></i>
                        </div>
                        @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="sm:col-span-2 relative">
                        <label for="password_confirmation"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Confirm
                            Password <span class="text-red-500">*</span></label>
                        <input type="password" wire:model="password_confirmation" id="password_confirmation"
                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Confirm password" required>

                        <!-- Toggle button -->
                        <div class="absolute inset-y-0 mt-6 right-0 flex items-center pr-5">
                            <i id="toggleConfirmPassword" class="fa-solid fa-eye text-gray-400 cursor-pointer"></i>
                        </div>
                        @error('password_confirmation')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>



                <div class="flex justify-between items-center space-y-2 mt-8">
                    <x-ghost-button onclick="history.back()" type="button">
                        Cancel
                    </x-ghost-button>
                    <x-button type="submit" wire:click="confirmCreate" wire:loading.attr="disabled">
                        Create User
                    </x-button>
                </div>
            </form>


            <!-- Create Confirmation Modal -->
            <x-dialog-modal wire:model.live="confirmCreateItem">
                <x-slot name="title">
                    {{ __('Create User') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to create this user?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmCreateItem', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-button class="ms-3 bg-green text-white" wire:click="saveUser" wire:loading.attr="disabled">
                        {{ __('Create User') }}
                    </x-button>
                </x-slot>
            </x-dialog-modal>

        </div>
    </div>
    <script>
        // Generic toggle function
        function setupPasswordToggle(inputId, toggleId) {
            const input = document.getElementById(inputId);
            const toggle = document.getElementById(toggleId);

            // Show toggle only when typing
            input.addEventListener("input", () => {
                toggle.style.display = input.value ? "block" : "none";
            });

            // Toggle password visibility
            toggle.addEventListener("click", function() {
                const isPassword = input.type === "password";
                input.type = isPassword ? "text" : "password";
                this.classList.toggle("fa-eye");
                this.classList.toggle("fa-eye-slash");
            });
        }

        // Apply to both fields
        setupPasswordToggle("password", "togglePassword");
        setupPasswordToggle("password_confirmation", "toggleConfirmPassword");
    </script>
</div>
