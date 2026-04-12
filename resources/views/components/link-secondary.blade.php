@props(['link' => '#', 'target' => '_self'])

<a href="{{ $link }}" target="{{ $target }}" {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-secondarycolor border border-secondarycolor rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-secondaryhcolor focus:outline-none focus:ring-2 focus:ring-secondarycolor focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</a>
