@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-4 pe-4 py-3 border-l-4 border-primarycolor text-start text-lg font-semibold text-white bg-primarycolor focus:outline-none focus:text-primarycolor focus:bg-primarycolor focus:border-primarycolor transition duration-150 ease-in-out'
            : 'block w-full ps-4 pe-4 py-3 border-l-4 border-transparent text-start text-lg font-semibold text-gray-700 hover:text-primarycolor hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-primarycolor focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
