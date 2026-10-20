<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 min-h-11 px-4 py-2.5 bg-dangercolor border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-dangerhcolor focus:bg-dangerhcolor active:bg-dangerhcolor focus:outline-none focus:ring-2 focus:ring-dangerhcolor focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    <svg class="h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="-10 -200 970 960">
        <path fill="currentColor" d="M200 648v-600h-40v-80h200v-40h240v40h200v80h-40v600h-560zM280 568h400v-520h-400v520zM360 488h80v-360h-80v360zM520 488h80v-360h-80v360zM280 48v0v520v0v-520z" class="cdn-arten"></path>
     </svg>
    {{ $slot->isEmpty() ? 'Eliminar' : $slot }}
</button>
