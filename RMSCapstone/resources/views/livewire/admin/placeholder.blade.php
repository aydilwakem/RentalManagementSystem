<div class="min-h-[550px] container mx-auto p-6 max-w-full" aria-busy="true">
    <div class="bg-white rounded-lg shadow-md overflow-x-auto border">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="text-sm text-gray-700 bg-gray-200">
                    <tr>
                        <th class="px-4 py-3"><div class="h-6 bg-gray-300 w-10 rounded animate-pulse"></div></th>
                        <th class="px-4 py-3"><div class="h-6 bg-gray-300 w-24 rounded animate-pulse"></div></th>
                        <th class="px-4 py-3"><div class="h-6 bg-gray-300 w-32 rounded animate-pulse"></div></th>
                        <th class="px-4 py-3"><div class="h-6 bg-gray-300 w-24 rounded animate-pulse"></div></th>
                        <th class="px-4 py-3"><div class="h-6 bg-gray-300 w-24 rounded animate-pulse"></div></th>
                        <th class="px-4 py-3"><div class="h-6 bg-gray-300 w-24 rounded animate-pulse"></div></th>
                        <th class="px-4 py-3"><div class="h-6 bg-gray-300 w-24 rounded animate-pulse"></div></th>
                        <th class="px-4 py-3"><div class="h-6 bg-gray-300 w-20 rounded animate-pulse"></div></th>
                        <th class="px-4 py-3"><div class="h-6 bg-gray-300 w-20 rounded animate-pulse"></div></th>
                    </tr>
                </thead>
                <tbody class="animate-pulse">
                    @for ($i = 0; $i < 5; $i++)
                        <tr class="border-b">
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
