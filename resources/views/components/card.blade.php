@props([
    'title' => 'Card Title',
    'value' => '0',
    'icon' => 'fas fa-chart-bar',
    'color' => 'blue',
    'subtitle' => '',
    'trend' => null, // 'up' atau 'down'
    'trendValue' => '',
])

@php
    $colors = [
        'blue' => 'bg-gradient-to-br from-blue-500 to-blue-600',
        'green' => 'bg-gradient-to-br from-green-500 to-green-600',
        'red' => 'bg-gradient-to-br from-red-500 to-red-600',
        'yellow' => 'bg-gradient-to-br from-yellow-500 to-yellow-600',
        'purple' => 'bg-gradient-to-br from-purple-500 to-purple-600',
        'indigo' => 'bg-gradient-to-br from-indigo-500 to-indigo-600',
    ];
    
    $iconBg = [
        'blue' => 'bg-blue-100 text-blue-600',
        'green' => 'bg-green-100 text-green-600',
        'red' => 'bg-red-100 text-red-600',
        'yellow' => 'bg-yellow-100 text-yellow-600',
        'purple' => 'bg-purple-100 text-purple-600',
        'indigo' => 'bg-indigo-100 text-indigo-600',
    ];
@endphp

<div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
    <div class="{{ $colors[$color] ?? $colors['blue'] }} p-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-white/80 mb-1">{{ $title }}</p>
                <h3 class="text-3xl font-bold mb-2">{{ $value }}</h3>
                
                @if($subtitle)
                    <p class="text-xs text-white/70">{{ $subtitle }}</p>
                @endif

                @if($trend)
                    <div class="mt-2 flex items-center text-sm">
                        @if($trend === 'up')
                            <i class="fas fa-arrow-up mr-1"></i>
                            <span class="font-semibold">{{ $trendValue }}</span>
                            <span class="ml-1 text-white/80">dari bulan lalu</span>
                        @else
                            <i class="fas fa-arrow-down mr-1"></i>
                            <span class="font-semibold">{{ $trendValue }}</span>
                            <span class="ml-1 text-white/80">dari bulan lalu</span>
                        @endif
                    </div>
                @endif
            </div>
            
            <div class="{{ $iconBg[$color] ?? $iconBg['blue'] }} w-16 h-16 rounded-full flex items-center justify-center">
                <i class="{{ $icon }} text-2xl"></i>
            </div>
        </div>
    </div>
    
    @isset($footer)
        <div class="px-6 py-3 bg-gray-50 border-t border-gray-100">
            {{ $footer }}
        </div>
    @endisset
</div>
