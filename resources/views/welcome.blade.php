<x-guest-layout>
    <livewire:general-quiniela/>
    
    @push('modals')
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @stack('js')
    @endpush
</x-guest-layout>