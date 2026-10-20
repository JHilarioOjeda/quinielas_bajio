<div class="min-h-screen flex flex-col sm:justify-center items-center px-4 py-10 sm:py-0 bg-gray-50">
    <div>
        {{ $logo }}
    </div>

    <p class="mt-3 text-sm font-semibold uppercase tracking-widest text-primarycolor">
        Quinielas Bajío
    </p>

    <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-xl overflow-hidden rounded-xl border-t-4 border-primarycolor">
        {{ $slot }}
    </div>
</div>
