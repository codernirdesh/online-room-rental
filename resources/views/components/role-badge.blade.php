@props(['role'])

@php
$classes = match($role) {
    'admin' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
    'owner' => 'bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200',
    default => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium $classes"]) }}>
    {{ ucfirst($role) }}
</span>
