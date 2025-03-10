<div>
    <section class="mt-10">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <div class="bg-white-500 relative shadow-md sm:rounded-lg overflow-hidden">

                <!-- Create Activity Button -->
                <div class="flex items-center justify-between p-4">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition"
                        onclick="window.location.href='{{ route('admin.create-activity') }}'">
                        + Create Activity
                    </button>
                </div>

                <!-- Search Bar -->
                <div class="flex items-center justify-between p-4">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2"
                            placeholder="Search Activity" required="">
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3">ID</th>
                                <th scope="col" class="px-4 py-3">Name</th>
                                <th scope="col" class="px-4 py-3">Description</th>
                                <th scope="col" class="px-4 py-3">Amount</th>
                                <th scope="col" class="px-4 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $activity)
                                <tr class="border-b">
                                    <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $activity->id }}
                                    </th>
                                    <td class="px-4 py-3"> {{ $activity->name }}</td>
                                    <td class="px-4 py-3"> {{ $activity->description }}</td>
                                    <td class="px-4 py-3"> {{ number_format($activity->amount, 2) }}</td>
                                    <td class="px-4 py-3 flex items-center justify-center space-x-4">
                                        <!-- View Icon -->
                                        <i class="fas fa-eye text-blue-500 cursor-pointer" wire:navigate
                                            href="{{ route('admin.view-activity', ['activity' => $activity->id]) }}">
                                        </i>

                                        <!-- Edit Icon -->
                                        <i class="fas fa-edit text-green-500 cursor-pointer" wire:navigate
                                            href="{{ route('admin.edit-activity', ['activity' => $activity->id]) }}">
                                        </i>

                                        <!-- Delete Icon -->
                                        <i class="fas fa-trash-alt text-red-500 cursor-pointer"
                                            wire:click="deleteActivity({{ $activity->id }})">
                                        </i>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="py-4 px-3">
                    <div class="flex">
                        <div class="flex space-x-4 items-center mb-3">
                            <label class="w-32 text-sm font-medium text-gray-900">Per Page</label>
                            <select wire:model.live="perPage"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                    {{ $activities->links() }}
                </div>
            </div>
        </div>
    </section>
</div>