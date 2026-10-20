<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 min-h-11 px-5 py-2.5 bg-dangercolor border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-dangerhcolor focus:bg-dangerhcolor active:bg-dangerhcolor focus:outline-none focus:ring-2 focus:ring-dangerhcolor focus:ring-offset-2 transition ease-in-out duration-150']) }}>
     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 flex-shrink-0">
        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm3 10.5a.75.75 0 0 0 0-1.5H9a.75.75 0 0 0 0 1.5h6Z" clip-rule="evenodd" />
      </svg>
    {{ $slot }}
</button>
