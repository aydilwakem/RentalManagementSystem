<!-- day-tour-step-one.blade.php -->
<div class="space-y-6">
    <h2 class="text-2xl font-semibold text-gray-800">Select Your Day Tour</h2>

    <!-- Date Selection -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Tour Date</label>
        <input type="date" wire:model.live="tourDate" 
            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
            class="border border-gray-300 rounded-lg px-4 py-2 w-full max-w-xs">
        @error('tourDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        
        @if($tourDate)
            <p class="text-sm text-gray-500 mt-1">
                Showing rates for: {{ \Carbon\Carbon::parse($tourDate)->format('M d, Y') }} ({{ $this->getDayType($tourDate) }})
            </p>
        @endif
    </div>

    <!-- Available Tours -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($availableTours as $tour)
            <div class="relative">
                <div class="border-2 rounded-lg overflow-hidden transition-all duration-200 cursor-pointer
                    {{ $selectedTour && $selectedTour->id == $tour->id ? 'border-green-500 bg-green-50 shadow-md' : 'border-gray-200 hover:border-gray-300' }}"
                    wire:click="selectTour({{ $tour->id }})">
                    
                    @if($selectedTour && $selectedTour->id == $tour->id)
                        <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-semibold z-10">
                            <i class="fas fa-check mr-1"></i> Selected
                        </div>
                    @endif

                    <img src="{{ $tour->main_image_url }}" alt="{{ $tour->name }}" 
                         class="w-full h-40 object-cover">
                    
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $tour->name }}</h3>
                        
                        <!-- Show ALL rates with availability indicators -->
                        <div class="space-y-2">
                            @if($tour->activeRates && count($tour->activeRates) > 0)
                                @foreach($tour->activeRates as $rate)
                                    @php
                                        $isAvailableForDate = $this->tourDate ? $rate->day_type === $this->getDayType($this->tourDate) : true;
                                        $isSelected = $selectedTour && $selectedTour->id == $tour->id && $selectedRate && $selectedRate->id == $rate->id;
                                    @endphp
                                    
                                    <div class="text-sm {{ $isSelected ? 'text-green-600 font-semibold' : ($isAvailableForDate ? 'text-gray-600' : 'text-gray-400') }}">
                                        <div class="flex items-center justify-between">
                                            <span>
                                                • {{ $rate->rate_name }} - ₱{{ number_format($rate->adult_rate, 2) }}
                                            </span>
                                            @if(!$isAvailableForDate && $this->tourDate)
                                                <span class="text-xs text-orange-500" title="Not available for selected date">
                                                    <i class="fas fa-calendar-times"></i>
                                                </span>
                                            @endif
                                        </div>
                                        @if($isAvailableForDate && $this->tourDate)
                                            <div class="text-xs text-green-500">
                                                ✓ Available for {{ $this->getDayType($this->tourDate) }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center p-2 bg-yellow-50 border border-yellow-200 rounded text-yellow-700 text-sm">
                                    No rates configured
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($availableTours->isEmpty())
        <div class="text-center py-8">
            <p class="text-gray-500">No day tours available.</p>
        </div>
    @endif

    <!-- Selected Tour Summary -->
    @if($selectedTour && $selectedRate)
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mt-6">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-semibold text-green-800 flex items-center">
                        <i class="fas fa-check-circle mr-2 text-green-600"></i>
                        Tour Selected
                    </h4>
                    <p class="text-sm text-green-700 mt-1">
                        <strong>{{ $selectedTour->name }}</strong> - {{ $selectedRate->rate_name }}
                    </p>
                    <p class="text-xs text-green-600">
                        Adult: ₱{{ number_format($selectedRate->adult_rate, 2) }} | 
                        Child: ₱{{ number_format($selectedRate->kid_rate, 2) }}
                    </p>
                    @if($this->tourDate && $selectedRate->day_type !== $this->getDayType($this->tourDate))
                        <p class="text-xs text-orange-600 mt-1">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            This rate is for {{ $selectedRate->day_type }} days, but you selected a {{ $this->getDayType($this->tourDate) }}
                        </p>
                    @endif
                </div>
                <button wire:click="selectTour(null)" 
                        class="text-red-500 hover:text-red-700 text-sm font-medium">
                    <i class="fas fa-times mr-1"></i> Change
                </button>
            </div>
        </div>
    @endif
</div>