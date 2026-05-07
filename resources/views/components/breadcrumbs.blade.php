@props(['items' => []])

<nav class="text-sm text-gray-700" aria-label="Breadcrumb">
    <ol class="flex space-x-2">
        @foreach ($items as $item)
            @if (!$loop->last)
                <li>
                    <a href="{{ $item['url'] }}" class="text-gray-600 hover:underline dark:text-gray-400">{{ $item['label'] }}</a>
                    <span><i class="fa-solid fa-chevron-right text-xs dark:text-gray-500"></i></span>
                </li>
            @else
                <li class="text-green-600 dark:text-green-400">{{ $item['label'] }}</li>
            @endif
        @endforeach
    </ol>
</nav>
