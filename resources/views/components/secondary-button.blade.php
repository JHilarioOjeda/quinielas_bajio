<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 min-h-11 px-5 py-2.5 bg-secondarycolor border border-secondarycolor rounded-lg font-semibold text-sm text-white shadow-sm hover:bg-secondaryhcolor focus:outline-none focus:ring-2 focus:ring-secondarycolor focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
