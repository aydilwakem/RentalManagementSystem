<div class="shadow-lg rounded-lg p-6 bg-white max-w-2xl mx-auto">
    <section class="bg-white">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900">Edit User</h2>
            <form wire:submit.prevent="updateUser">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <!-- Name -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                        <input type="text" wire:model="name" id="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Type user name" required>
                    </div>

                    <!-- Email -->
                    <div class="sm:col-span-2">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                        <input type="email" wire:model="email" id="email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Type user email" required>
                    </div>

                    <!-- Password -->
                    <div class="sm:col-span-2">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                        <input type="password" wire:model="password" id="password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="">
                    </div>

                    <!-- Role Selection -->
                    <div class="sm:col-span-2">
                        <label for="role" class="block mb-2 text-sm font-medium text-gray-900">Select Role</label>
                        <select id="role" wire:model="selectedRole"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                            <option value="" disabled>Select a role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-blue-700">
                    Update User
                </button>
            </form>
        </div>
    </section>
</div>