@props([
    'type' => 'info', // success, error, warning, info
    'dismissible' => false,
])

@php
$types = [
    'success' => 'bg-green-50 border-green-200 text-green-800',
    'error' => 'bg-red-50 border-red-200 text-red-800',
    'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
    'info' => 'bg-blue-50 border-blue-200 text-blue-800',
];
$icons = [
    'success' => 'check-circle',
    'error' => 'x-circle',
    'warning' => 'exclamation-triangle',
    'info' => 'information-circle',
];
$classes = 'border-l-4 p-4 rounded ' . $types[$type];
@endphp

<div {{ $attributes->merge(['class' => $classes, 'role' => 'alert']) }} x-data="{ show: true }" x-show="show">
    <div class="flex items-center">
        <x-icon :name="$icons[$type]" class="mr-3 h-5 w-5" />
        <div class="flex-1">
            {{ $slot }}
        </div>
        @if($dismissible)
            <button @click="show = false" class="ml-4 text-gray-400 hover:text-gray-600">
                <x-icon name="x" class="h-5 w-5" />
            </button>
        @endif
    </div>
</div>

