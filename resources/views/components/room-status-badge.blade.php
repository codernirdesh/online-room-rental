@props(['status'])

@php
$classes = match($status) {
    'available' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
    'booked' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
    default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium $classes"]) }}>
    {{ ucfirst($status) }}
</span>
