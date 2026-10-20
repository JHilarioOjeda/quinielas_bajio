<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 min-h-11 px-5 py-2.5 bg-primarycolor border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-primaryhovercolor focus:bg-primaryhovercolor active:bg-primaryhovercolor focus:outline-none focus:ring-2 focus:ring-primarycolor focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 flex-shrink-0">
        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
    </svg>
    {{ $slot }}
</button>
