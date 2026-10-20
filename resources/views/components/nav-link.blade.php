@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-primarycolor text-base font-semibold leading-5 text-primarycolor focus:outline-none focus:border-primarycolor transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-base font-semibold leading-5 text-gray-600 hover:text-primarycolor hover:border-gray-300 focus:outline-none focus:text-primarycolor focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
