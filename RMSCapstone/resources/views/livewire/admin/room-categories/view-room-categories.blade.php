<div>
    <section class="mt-10">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <!-- Start coding here -->
            <div class=" bg-white-500 relative shadow-md sm:rounded-lg overflow-hidden">

                <!-- Create Room Button -->
                <div class="flex items-center justify-between p-4">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition"
                        onclick="window.location.href='{{ route('admin.create-room-category') }}'">
                        + Create Category
                    </button>
                </div>

                <div class="flex items-center justify-between d p-4">
                    <div class="flex">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 " fill="currentColor"
                                    viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 "
                                placeholder="Search" required="">
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <div class="flex space-x-3 items-center">
                            <label class="w-40 text-sm font-medium text-gray-900">User Type :</label>
                            <select
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                <option value="">All</option>
                                <option value="0">User</option>
                                <option value="1">Admin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3">ID</th>
                                <th scope="col" class="px-4 py-3">Name</th>
                                <th scope="col" class="px-4 py-3">Description</th>
                                <th scope="col" class="px-4 py-3">Amenities</th>
                                <th scope="col" class="px-4 py-3 text-center">Actions</th>
                                {{--<th scope="col" class="px-4 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>--}}
                            </tr>
                        </thead>
                        @foreach($roomCategories as $category)
                            <tr class="border-b">
                                <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $category->id }}
                                </th>
                                <td class="px-4 py-3">{{ $category->name }}</td>
                                <td class="px-4 py-3">{{ $category->description ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    @if($category->amenities->count() > 0)
                                        {{ implode(', ', $category->amenities->pluck('name')->toArray()) }}
                                    @else
                                        No Amenities
                                    @endif
                                </td>
                                <td class="px-4 py-3 flex items-center justify-center space-x-4">
                                    <!-- View Icon -->
                                    <i class="fas fa-eye text-blue-500 cursor-pointer"
                                        wire:click="viewCategory({{ $category->id }})">
                                    </i>

                                    <!-- Edit Icon -->
                                    <i class="fas fa-edit text-yellow-500 curssor-pointer"></i>

                                    <!-- Delete Icon -->
                                    <i class="fas fa-trash-alt text-red-500 cursor-pointer"
                                        wire:click="deleteCategory({{ $category->id }})">
                                    </i>

                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                <div class="py-4 px-3">
                    <div class="flex ">
                        <div class="flex space-x-4 items-center mb-3">
                            <label class="w-32 text-sm font-medium text-gray-900">Per Page</label>
                            <select
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>