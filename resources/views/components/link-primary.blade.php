@props(['link' => '#', 'target' => '_self'])

<a href="{{ $link }}" target="{{ $target }}" {{ $attributes->merge(['class' => 'inline-flex items-center px-2 py-1 bg-primarycolor border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primaryhovercolor focus:bg-primaryhovercolor active:bg-primaryhovercolor focus:outline-none focus:ring-2 focus:ring-primarycolor focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</a>
