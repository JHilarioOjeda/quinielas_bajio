<x-app-layout>
    <livewire:admin.tickets />

    @push('modals')
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @stack('js')
    @endpush
</x-app-layout>
