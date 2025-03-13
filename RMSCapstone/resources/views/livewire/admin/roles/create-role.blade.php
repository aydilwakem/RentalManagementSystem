<div class="shadow-lg rounded-lg p-6 bg-white max-w-2xl mx-auto">
    <section class="bg-white">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900">Add new Role</h2>

            {{-- Display Validation Errors --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li class="py-1">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form wire:submit.prevent="saveRole">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <!-- Name of Role -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                        <input type="text" wire:model="name" id="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Enter Role" required>
                    </div>

                    <!-- Permissions List -->
                    <div class="sm:col-span-2">
                        <label for="permissions"
                            class="block mb-2 text-sm font-medium text-gray-900">Permissions</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($permissions as $permission)
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}"
                                        class="rounded border-gray-300">
                                    <span>{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>



                </div>

                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-blue-700"
                    wire:loading.attr="disabled" wire:target="image">
                    Add Role
                </button>

            </form>
        </div>
    </section>
</div>