@props(['link' => '#', 'target' => '_self'])

<a href="{{ $link }}" target="{{ $target }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 min-h-11 px-5 py-2.5 bg-primarycolor border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-primaryhovercolor focus:bg-primaryhovercolor active:bg-primaryhovercolor focus:outline-none focus:ring-2 focus:ring-primarycolor focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</a>
