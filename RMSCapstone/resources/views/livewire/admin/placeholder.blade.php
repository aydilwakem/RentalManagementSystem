<div class="min-h-[550px] container mx-auto p-6 max-w-full">
    <!--Header Buttons -->
    <div class="flex items-center justify-between mb-4 animate-pulse">
        <div class="h-9 w-32 bg-gray-200 rounded-lg dark:bg-gray-500"></div>
        <div class="h-9 w-40 bg-gray-200 rounded-lg dark:bg-gray-500"></div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-md overflow-x-auto border animate-pulse dark:bg-gray-800 dark:border-gray-700">
        <div class="flex justify-between p-4 space-x-4">
            <div class="flex space-x-2 w-full">
                <div class="h-10 w-56 bg-gray-200 rounded-lg dark:bg-gray-500"></div>
                <div class="h-10 w-24 bg-gray-200 rounded-lg dark:bg-gray-500"></div>
            </div>
            <div class="h-10 w-56 bg-gray-200 rounded-lg dark:bg-gray-500"></div>
        </div>

        <!-- Table Headers -->
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="text-sm text-gray-700 bg-gray-200 dark:bg-gray-800 ">
                    <tr>
                        <th class="px-4 py-3"><div class="h-6 bg-gray-300 w-10 rounded dark:bg-gray-600"></div></th>
                        <th class="px-4 py-3"><div class="h-6 bg-gray-300 w-24 rounded dark:bg-gray-600"></div></th>
                        <th class="px-4 py-3"><div class="h-6 bg-gray-300 w-32 rounded dark:bg-gray-600"></div></th>
                        <th class="px-4 py-3"><div class="h-6 bg-gray-300 w-24 rounded dark:bg-gray-600"></div></th>
                        <th class="px-4 py-3"><div class="h-6 bg-gray-300 w-24 rounded dark:bg-gray-600"></div></th>
                        <th class="px-4 py-3"><div class="h-6 bg-gray-300 w-24 rounded dark:bg-gray-600"></div></th>
                        <th class="px-4 py-3"><div class="h-6 bg-gray-300 w-24 rounded dark:bg-gray-600"></div></th>
                        <th class="px-4 py-3"><div class="h-6 bg-gray-300 w-20 rounded dark:bg-gray-600"></div></th>
                        <th class="px-4 py-3"><div class="h-6 bg-gray-300 w-20 rounded dark:bg-gray-600"></div></th>
                    </tr>
                </thead>

                <!-- Table Rows -->
                <tbody>
                    @for ($i = 0; $i < 5; $i++)
                        <tr class="border-b odd:dark:bg-gray-700 even:dark:bg-gray-800">
                            @for ($j = 0; $j < 9; $j++)
                                <td class="px-4 py-4">
                                    <div class="h-4 bg-gray-200 rounded w-full"></div>
                                </td>
                            @endfor
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
