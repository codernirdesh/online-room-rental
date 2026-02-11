@props(['status'])

@php
$classes = match($status) {
    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
    'paid' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
    'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
    'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
    'cancelled' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
    default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
};

$label = match($status) {
    'pending' => 'Awaiting Payment',
    'paid' => 'Payment Submitted',
    'approved' => 'Confirmed',
    default => ucfirst($status),
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium $classes"]) }}>
    {{ $label }}
</span>
